<?php

namespace app\models;

use StdClass;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use common\models\User;
use yii\helpers\FileHelper;

/**
 * Класс для работы с моделью заданий
 *
 * Class Task
 *
 * @package app\models
 */
class Task extends ActiveRecord
{
    /** @var string строка со статусом нового задания */
    public const STATUS_NEW = 'new';
    /** @var string строка со статусом выполняемого задания */
    public const STATUS_EXECUTION = 'execution';
    /** @var string строка со статусом завершенного задания */
    public const STATUS_COMPLETED = 'completed';
    /** @var string строка со статусом отменённого задания */
    public const STATUS_CANCELED = 'canceled';
    /** @var string строка со статусом проваленного задания */
    public const STATUS_FAILING = 'failing';
    /** @var string строка со статусом просроченного задания */
    public const STATUS_EXPIRED = 'expired';

    /**
     * Возвращает проверку, является ли задание новым
     *
     * @param int $userId идентификатор пользователя
     *
     * @return bool результат проверки
     */
    public function getIsAvailableChat(int $userId): bool
    {
        return $this->executor_id
            && ($this->getIsAuthor($userId)
                || $this->getIsSelectedExecutor($userId));
    }

    /**
     * Возвращает проверку, является ли задание новым
     *
     * @return bool результат проверки
     */
    public function getIsStatusNew(): bool
    {
        return $this->status === self::STATUS_NEW;
    }

    /**
     * Возвращает проверку, может ли пользователь с указанным id выполните
     * действие - отклик на задание
     *
     * @param int $userId идентификатор пользователя
     *
     * @return bool результат проверки
     */
    public function getIsAvailableActionRespond(int $userId): bool
    {
        return !$this->getIsAuthor($userId) && !$this->getIsUserRespond($userId);
    }

    /**
     * Возвращает проверку, может ли пользователь с указанным id выполните
     * действие - завершение задания
     *
     * @param int $userId идентификатор пользователя
     *
     * @return bool результат проверки
     */
    public function getIsAvailableActionCompleted(int $userId): bool
    {
        return $this->getIsAuthor($userId) && $this->status === self::STATUS_EXECUTION;
    }

    /**
     * Возвращает проверку, может ли пользователь с указанным id выполните
     * действие - отмена задания
     *
     * @param int $userId идентификатор пользователя
     *
     * @return bool результат проверки
     */
    public function getIsAvailableActionCanceled(int $userId): bool
    {
        return $this->getIsAuthor($userId) && $this->status === self::STATUS_NEW;
    }

    /**
     * Возвращает проверку, может ли пользователь с указанным id выполните
     * действие - отказ от задания
     *
     * @param int $userId идентификатор пользователя
     *
     * @return bool результат проверки
     */
    public function getIsAvailableActionRefusal(int $userId): bool
    {
        return !$this->getIsAuthor($userId) && $this->getIsUserRespond($userId)
            && $this->getIsSelectedExecutor($userId);
    }

    /**
     * Возвращает проверку, было ли заверщено данное задание
     *
     * @return bool результат проверки
     */
    public function getIsFinishing(): bool
    {
        return ($this->status === self::STATUS_COMPLETED
                || $this->status === self::STATUS_FAILING)
            && $this->executor;
    }

    /**
     * Назначение для данного задания нового исполнителя
     *
     * @param int $executorId идентификатор назначенного исполнителя
     */
    public function setExecutor(int $executorId): void
    {
        $this->status = self::STATUS_EXECUTION;
        $this->executor_id = $executorId;
        $this->save();
    }

    /**
     * Сохранение файлов для указанного задания
     *
     * @param array  $files список файлов к заданию
     * @param string $path  адрес директории для хранения файлов
     *
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     * @throws \yii\web\ServerErrorHttpException
     */
    public function setFiles(array $files, string $path): void
    {
        $pathTaskDir = "$path/$this->id";
        if (file_exists($pathTaskDir)) {
            FileHelper::removeDirectory($pathTaskDir);
        }
        FileHelper::createDirectory($pathTaskDir);
        (new TaskFile(['path' => $pathTaskDir]))->setFiles($this->id, $files);
    }

    /**
     * Проверка права пользователя на создание сообщения к данному заданию
     *
     * @param int $userId идентификатор пользователя
     *
     * @return bool резльутат проверки, есть право на создание, или нет
     */
    public function getAccessCheckMessageCreate(int $userId): bool
    {
        return $this->getIsSelectedExecutor($userId)
            || $this->getIsAuthor($userId);
    }

    /**
     * Проверка, откликался ли уже пользователь с указанным
     * идентификатором на это задание
     *
     * @param int $userId идентификатор пользователя
     *
     * @return bool результат проверки, откликался ли пользователь на задание
     */
    public function getIsUserRespond(int $userId): bool
    {
        return TaskRespond::find()->where([
            'task_id' => $this->id,
            'user_id' => $userId,
        ])->exists();
    }

    /**
     * Проверка, является ли пользователь с указанным идентификатором
     * автором этого задания
     *
     * @param int $userId идентификатор пользователя
     *
     * @return bool результат проверки, является ли пользователь автором задания
     */
    public function getIsAuthor(int $userId): bool
    {
        return $this->author_id === $userId;
    }

    /**
     * Проверка, является ли пользователь с указанным идентификатором
     * исполнителем этого задания
     *
     * @param int $userId идентификатор пользователя
     *
     * @return bool результат проверки, является ли пользователь исполнителем задания
     */
    public function getIsSelectedExecutor(int $userId): bool
    {
        return $this->executor_id === $userId;
    }

    /**
     * Получение данных локации задания через яндекс карты
     *
     * @return StdClass|null
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function getLocation(): ?StdClass
    {
        return ($this->latitude && $this->longitude)
            ? Yii::$container->get('yandexMap')
                ->getAddressByPositions($this->latitude, $this->longitude)
            : null;
    }

    /**
     * Получение количества сообщений для данного задания
     *
     * @return int число с количеством сообщений
     */
    public function getMessagesCount(): int
    {
        return Message::find()->where(['task_id' => $this->id])->count();
    }

    /**
     * Создание связи с исполнителем данного задания
     *
     * @return ActiveQuery
     */
    public function getExecutor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'executor_id']);
    }

    /**
     * Создание связи с категорией данного задания
     *
     * @return ActiveQuery
     */
    public function getCategory(): ActiveQuery
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    /**
     * Создание связи с автором данного задания
     *
     * @return ActiveQuery
     */
    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    /**
     * Создание с отзывом к данному заданию
     *
     * @return ActiveQuery
     */
    public function getReview(): ActiveQuery
    {
        return $this->hasOne(Review::class, ['task_id' => 'id']);
    }

    /**
     * Создание связи с файлами к данному заданию
     *
     * @return ActiveQuery
     */
    public function getFiles(): ActiveQuery
    {
        return $this->hasMany(TaskFile::class, ['task_id' => 'id'])->asArray();
    }

    /**
     * Создание связи с откликами к данному заданию
     *
     * @return ActiveQuery
     */
    public function getResponds(): ActiveQuery
    {
        return $this->hasMany(TaskRespond::class, ['task_id' => 'id']);
    }

    /**
     * Получение массива со списком всех статусов доступных для заданий
     *
     * @return array массив со списком всех статусов доступных для заданий
     */
    public static function getStatusList(): array
    {
        return [
            self::STATUS_NEW,
            self::STATUS_EXECUTION,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELED,
            self::STATUS_FAILING,
            self::STATUS_EXPIRED,
        ];
    }

    /**
     * Получение имени таблицы модели
     *
     * @return string имя таблицы модели
     */
    public static function tableName(): string
    {
        return 'task';
    }

    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     */
    public function rules(): array
    {
        return [
            [['title', 'description', 'category_id', 'author_id'], 'required'],
            [['author_id', 'category_id', 'executor_id'], 'integer'],
            [
                ['author_id', 'executor_id'],
                'exist',
                'targetClass' => User::class,
                'targetAttribute' => 'id',
            ],
            [
                'category_id',
                'exist',
                'targetClass' => Category::class,
                'targetAttribute' => 'id',
            ],
            ['price', 'integer', 'min' => 1],
            [['description'], 'string'],
            ['title', 'string', 'max' => 255],
            ['status', 'in', 'range' => self::getStatusList()],
            [
                'date_end',
                'match',
                'pattern' => '/^\d{4}-\d{2}-\d{2}( \d{2}:\d{2}:\d{2}$)?$/',
            ],
        ];
    }

    /**
     * Устанавливает статус текущего задания - отмененно
     */
    public function actionCancel(): void
    {
        $this->status = self::STATUS_CANCELED;
        $this->save();
    }

    /**
     * Возвращает задание в категорию новых, убирая старого исполнителя
     */
    public function actionRefusal(): void
    {
        $this->status = self::STATUS_NEW;
        $this->executor_id = null;
        $this->save();
    }

    /**
     * Завершает текущее задание устанавливая соответствующий статус
     *
     * @param bool $result результат выволненнения задания
     */
    public function finishing(bool $result): void
    {
        $this->status = $result ? self::STATUS_COMPLETED : self::STATUS_FAILING;
        $this->save();
    }

    /**
     * Метод для создания нового задания
     *
     * @param TaskCreate $model         модель с данными нового задания
     * @param string     $taskFilesPath адрес директории для сохранения файлов к заданию
     *
     * @return bool результат создания нового задания
     */
    public static function create(
        TaskCreate $model,
        string $taskFilesPath
    ): bool {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $task = new self([
                'author_id' => Yii::$app->user->identity->id,
                'title' => $model->title,
                'description' => $model->description,
                'category_id' => $model->categoryId,
                'price' => $model->price,
                'latitude' => $model->latitude,
                'longitude' => $model->longitude,
                'date_end' => $model->dateEnd,
                'city_id' => $model->cityId,
                'status' => self::STATUS_NEW,
            ]);
            $task->save();

            if ($model->files) {
                $task->setFiles($model->files, $taskFilesPath);
            }
            $transaction->commit();

            return true;
        } catch (\Exception $err) {
            $transaction->rollBack();
        }

        return false;
    }
}
