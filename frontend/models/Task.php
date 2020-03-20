<?php
namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use common\models\User;
use yii\helpers\Url;

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
    const STATUS_NEW = 'new';
    /** @var string строка со статусом выполняемого задания  */
    const STATUS_EXECUTION = 'execution';
    /** @var string строка со статусом завершенного задания  */
    const STATUS_COMPLETED = 'completed';
    /** @var string строка со статусом отменённого задания  */
    const STATUS_CANCELED = 'canceled';
    /** @var string строка со статусом проваленного задания  */
    const STATUS_FAILING = 'failing';
    /** @var string строка со статусом просроченного задания  */
    const STATUS_EXPIRED = 'expired';

    /**
     * Получение данных локации задания через яндекс карты
     *
     * @return |null
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function getLocation()
    {
        return ($this->latitude && $this->longitude) ? Yii::$container->get('yandexMap')
            ->getAddressByPositions($this->latitude, $this->longitude) : null;
    }

    /**
     * Получение название имени файла без лишнего текта
     *
     * @param string $fileName строка с адресом файла
     *
     * @return string строка с именем файла
     */
    public function getCorrectFileName(string $fileName): string
    {
        $arr = explode('/', $fileName);
        return end($arr);
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
     * Получение строки с ссылкой на страницу данного задания
     *
     * @return string строка с ссылкой на страницу данного задания
     */
    public function getCurrentTaskUrl(): string
    {
        return Url::to("/tasks/view/$this->id");
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
     * Получение строки с ссылкой на страницу со списком заданий
     *
     * @return string
     */
    public static function getBaseTasksUrl(): string
    {
        return Url::to("/tasks");
    }

    /**
     * Получение строки с ссылкой на страницу со списком заданий отфильтрованных по указанной категории
     *
     * @param int $categoryId категория для фильтрации заданий
     *
     * @return string строка с ссылкой на страницу со списком отфильтрованных заданий
     */
    public static function getUrlTasksByCategory(int $categoryId): string
    {
        return "/tasks?filter[category][]=$categoryId";
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
            [['title', 'description', 'category_id', 'author_id', 'price', 'executor_id', 'date_start', 'date_end', 'status'], 'safe'],
            [['title', 'description', 'category_id'], 'required'],
            [['author_id', 'category_id', 'price', 'executor_id'], 'integer'],
            [['description'], 'string'],
            [['title', 'status'], 'string', 'max' => 255],
        ];
    }
}
