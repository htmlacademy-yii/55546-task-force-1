<?php

namespace common\models;

use app\models\Category;
use app\models\City;
use app\models\EventRibbon;
use app\models\FavoriteExecutor;
use app\models\Review;
use app\models\Task;
use app\models\UserData;
use app\models\UserNotifications;
use app\models\UserPhoto;
use app\models\UserSettings;
use app\models\UserSpecialization;
use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * Класс для работы с моделью пользователя
 *
 * Class User
 *
 * @package common\models
 */
class User extends ActiveRecord implements IdentityInterface
{
    /** @var integer статус неактивного пользователя */
    public const STATUS_INACTIVE = 9;
    /** @var integer статус активного пользователя */
    public const STATUS_ACTIVE = 10;

    /** @var string роль пользовтеля клиента */
    public const ROLE_CLIENT = 'client';
    /** @var string роль пользовтеля исполнителя */
    public const ROLE_EXECUTOR = 'executor';

    /**
     * Проверка, является ли пользователь хозяином профиля
     *
     * @param int $userId идентификатор пользователя
     *
     * @return bool лоическое значение, является ли пользователь хозяином профиля
     */
    public function getIsOwnerProfile(int $userId): bool
    {
        return $this->id === $userId;
    }

    /**
     * Проверка, является ли пользователь исполнителем
     *
     * @return bool лоическое значение, является ли пользователь исполнителем
     */
    public function getIsExecutor(): bool
    {
        return $this->role === self::ROLE_EXECUTOR;
    }

    /**
     * Метод для получения информации, находится ли данный исполнитель
     * в списке избранного у указанного клиента
     *
     * @param int $executorId число с идентификатором исполнителя
     *
     * @return bool лоическое значение, находится ли данный исполнитель в списке избранного
     */
    public function getIsFavorite(int $executorId): bool
    {
        return FavoriteExecutor::find()->where([
            'client_id' => $this->id,
            'executor_id' => $executorId,
        ])->exists();
    }

    /**
     * Метод для получения рейтинга исполнителя
     *
     * @return float рейтинга исполнителя
     */
    public function getRating(): float
    {
        return round(Review::find()->where(['executor_id' => $this->id])
            ->average('rating'), 1);
    }

    /**
     * Метод для получения информации, является ли данный клиент заказчиком у
     * у указанного клиента
     *
     * @param int $executorId число с идентификатором исполнителя
     *
     * @return bool лоическое значение, является ли данный клиент заказчиком у исполнителя
     */
    public function getIsCustomer(int $executorId): bool
    {
        return Task::find()->where([
            'status' => Task::STATUS_EXECUTION,
            'executor_id' => $executorId,
            'author_id' => $this->id,
        ])->exists();
    }

    /**
     * Создание связи со списком событий для данного пользователя
     *
     * @return ActiveQuery список событий для данного пользователя
     */
    public function getEvents(): ActiveQuery
    {
        return $this->hasMany(EventRibbon::class, ['user_id' => 'id']);
    }

    /**
     * Метод для получения количества выполненных заданий данным исполнителем
     *
     * @return int количество выполненных заданий
     */
    public function getCompletedTasksCount(): int
    {
        return Task::find()
            ->where(['executor_id' => $this->id])
            ->andWhere(['!=', 'status', Task::STATUS_EXECUTION])
            ->count();
    }

    /**
     * Получение списка идентификаторов избранных исполнителей для данного пользователя
     *
     * @return array список идентификаторов избранных исполнителей для данного пользователя
     */
    public function getFavoriteExecutorsId(): array
    {
        return FavoriteExecutor::find()->select('executor_id')
            ->where(['client_id' => $this->id])->column();
    }

    /**
     * Получение общего поличества отзывов для данного исполнителя
     *
     * @return int общее поличество отзывов для данного исполнителя
     */
    public function getReviewsCount(): int
    {
        return Review::find()->where(['executor_id' => $this->id])->count();
    }

    /**
     * Получение общего поличества принятых заданий для данного исполнителя
     *
     * @return int общее поличество принятых заданий для данного исполнителя
     */
    public function getOrdersCount(): int
    {
        return Task::find()->where(['executor_id' => $this->id])->count();
    }

    /**
     * Создание связи с общими данными для текущего пользователя
     *
     * @return ActiveQuery общие данные для текущего пользователя
     */
    public function getUserData(): ActiveQuery
    {
        return $this->hasOne(UserData::class, ['user_id' => 'id']);
    }

    /**
     * Создание связи с уведомлениями для данного пользователя
     *
     * @return ActiveQuery уведомления для данного пользователя
     */
    public function getUserNotifications(): ActiveQuery
    {
        return $this->hasOne(UserNotifications::class, ['user_id' => 'id']);
    }

    /**
     * Создание связи с настройками для данного пользователя
     *
     * @return ActiveQuery настройки для данного пользователя
     */
    public function getUserSettings(): ActiveQuery
    {
        return $this->hasOne(UserSettings::class, ['user_id' => 'id']);
    }

    /**
     * Создание связи с выбранным у данного пользователя городом
     *
     * @return ActiveQuery выбранный у данного пользователя город
     */
    public function getCity(): ActiveQuery
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
    }

    /**
     * Получение списка идентификаторов специализаций для данного пользователя
     *
     * @return array список идентификаторов специализаций для данного пользователя
     */
    public function getSpecializationsId(): array
    {
        return UserSpecialization::find()->select('category_id')
            ->where(['user_id' => $this->id])->asArray()->column();
    }

    /**
     * Создание связи со списком специализаций данного пользователя
     *
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getUserSpecializations(): ActiveQuery
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])
            ->viaTable('user_specialization', ['user_id' => 'id']);
    }

    /**
     * Создание связи с созданными данным пользователем заданиями
     *
     * @return ActiveQuery список с созданными данным пользователем заданиями
     */
    public function getTasks(): ActiveQuery
    {
        return $this->hasMany(Task::class, ['author_id' => 'id']);
    }

    /**
     * Создание связи с фотографиями работ данного пользователя
     *
     * @return ActiveQuery список с фотографиями работ данного пользователя
     */
    public function getPhotos(): ActiveQuery
    {
        return $this->hasMany(UserPhoto::class, ['user_id' => 'id']);
    }

    /**
     * Создание связи с отзывами оставленными для данного исполнителя
     *
     * @return ActiveQuery список с отзывами оставленными для данного исполнителя
     */
    public function getReviews(): ActiveQuery
    {
        return $this->hasMany(Review::class, ['executor_id' => 'id']);
    }

    /**
     * @return int|mixed|string
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @return string
     */
    public function getAuthKey(): string
    {
        return $this->auth_key;
    }

    /**
     * Получение имени таблицы модели
     *
     * @return string имя таблицы модели
     */
    public static function tableName(): string
    {
        return 'user';
    }

    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     */
    public function rules(): array
    {
        return [
            [['login', 'email', 'password', 'role'], 'required'],
            [['login', 'email'], 'trim'],
            ['login', 'string', 'min' => 2, 'max' => 255],
            [
                'login',
                'unique',
                'message' => 'Данное имя уже занято',
            ],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [
                'email',
                'unique',
                'message' => 'Данный email уже используется',
            ],
            ['password', 'string', 'min' => 6],
            ['city_id', 'integer'],
            [
                'city_id',
                'exist',
                'targetClass' => City::class,
                'targetAttribute' => 'id',
                'message' => 'Указанный город не найден в нашей базе данных',
            ],
            [
                'role',
                'in',
                'range' => [self::ROLE_CLIENT, self::ROLE_EXECUTOR],
            ],
        ];
    }

    /**
     * @param int|string $id
     *
     * @return ActiveRecord
     */
    public static function findIdentity($id): ActiveRecord
    {
        return static::findOne((int)$id);
    }

    /**
     * @param mixed $token
     * @param null  $type
     *
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null): void
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     *
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     *
     * @return static|null
     */
    public static function findByVerificationToken($token): ActiveRecord
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     *
     * @return bool
     */
    public static function isPasswordResetTokenValid($token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];

        return $timestamp + $expire >= time();
    }

    /**
     * @param string $authKey
     *
     * @return bool
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     *
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password,
            $this->password_hash);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey(): void
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken(): void
    {
        $this->password_reset_token
            = Yii::$app->security->generateRandomString().'_'.time();
    }

    public function generateEmailVerificationToken(): void
    {
        $this->verification_token = Yii::$app->security->generateRandomString()
            .'_'.time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken(): void
    {
        $this->password_reset_token = null;
    }

    /**
     * Обновляет время последней активности пользователя
     *
     * @throws \yii\db\Exception
     */
    public function updateLastActivity(): void
    {
        Yii::$app->db->createCommand("UPDATE user SET last_activity = NOW() WHERE id = :id",
            [':id' => $this->id])->execute();
    }

    /**
     * Создание нового пользователя в базе данных
     *
     * @param array $data данные нового пользователя
     * @param array $dataInit дополнительные данные для инициализации нового пользователя
     *
     * @return User|null объект нового созданного пользователя
     */
    public static function create(
        array $data,
        array $dataInit = []
    ): ?User {
        $user = null;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $user = new self($data);
            $user->save();
            (new UserData(ArrayHelper::merge([
                'user_id' => $user->id,
                'description' => '',
                'other_messenger' => '',
                'avatar' => '',
                'views' => 0,
                'success_counter' => 0,
                'failing_counter' => 0,
            ], $dataInit['data'] ?? [])))->save();
            (new UserNotifications(ArrayHelper::merge([
                'user_id' => $user->id,
                'is_new_message' => 0,
                'is_task_actions' => 0,
                'is_new_review' => 0,
            ], $dataInit['notifications'] ?? [])))->save();
            (new UserSettings(ArrayHelper::merge([
                'user_id' => $user->id,
                'is_hidden_contacts' => 0,
                'is_hidden_profile' => 0,
            ], $dataInit['settings'] ?? [])))->save();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
        }

        return $user;
    }
}
