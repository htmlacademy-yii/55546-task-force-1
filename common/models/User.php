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
use yii\helpers\Url;
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
    const STATUS_INACTIVE = 9;
    /** @var integer статус активного пользователя */
    const STATUS_ACTIVE = 10;

    /** @var string роль пользовтеля клиента */
    const ROLE_CLIENT = 'client';
    /** @var string роль пользовтеля исполнителя */
    const ROLE_EXECUTOR = 'executor';

    /** @var string тип сортировки по рейтингу */
    const SORT_TYPE_RATING = 'rating';
    /** @var string тип сортировки по заказам */
    const SORT_TYPE_ORDERS = 'orders';
    /** @var string тип сортировки по популярности */
    const SORT_TYPE_POPULARITY = 'popularity';

    /**
     * Метод для получения строки с ссылкой на страницу текущего пользователя
     *
     * @return string строка с ссылкой на страницу текущего пользователя
     */
    public function getCurrentUserUrl(): string
    {
        return self::getUserUrl($this->id);
    }

    /**
     * Метод для получения строки с ссылкой на страницу выбора данного пользователя в качестве фаворита
     *
     * @return string строка с ссылкой на страницу выбора данного пользователя в качестве фаворита
     */
    public function getFavoriteUrl(): string
    {
        return "/users/select-favorite?userId={$this->id}";
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
     * Получение списка специализаций для данного пользователя
     *
     * @return array список специализаций для данного пользователя
     */
    public function getSpecializations(): array
    {
        return Category::findAll($this->getSpecializationsId());
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
     * Общий метод для обработки ссылки на автара пользователя
     *
     * @param string $avatar необработанная строка ссылки на аватар
     *
     * @return string обработанная строка ссылки на аватар
     */
    public static function getCorrectAvatar(string $avatar): string
    {
        if (!empty($avatar)) {
            return preg_match('/^http/', $avatar) ? $avatar : Url::to("/$avatar");
        }

        return Url::to('/img/user-photo.png');
    }

    /**
     * Метод для получения строки с ссылкой на страницу нужного пользователя
     *
     * @param int $id идентификатор нужного пользователя
     *
     * @return string строка с ссылкой на страницу нужного пользователя
     */
    public static function getUserUrl(int $id): string
    {
        return "/users/view/$id";
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password_hash
            = Yii::$app->security->generatePasswordHash($password);
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
            ['login', 'trim'],
            ['login', 'required'],
            ['login', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [
                'email',
                'unique',
                'message' => 'This email address has already been taken.',
            ],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['name', 'safe'],
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
}
