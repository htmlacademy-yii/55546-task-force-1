<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\models\{Category, UserData, Review, TaskFile, TaskRespond};

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property int|null $author_id
 * @property string|null $title
 * @property string|null $description
 * @property int|null $category_id
 * @property int|null $price
 * @property string|null $date_start
 * @property string|null $date_end
 * @property int|null $executor_id
 * @property string|null $status
 */
class Task extends ActiveRecord
{
    const ROLE_OWNER = 'owner'; // заказчик
    const ROLE_EXECUTOR = 'executor'; // исполнитель

    const STATUS_NEW = 'new'; // новое
    const STATUS_EXECUTION = 'execution'; // выполняется
    const STATUS_COMPLETED = 'completed'; // завершено
    const STATUS_CANCELED = 'canceled'; // отменено
    const STATUS_FAILING = 'failing'; // провалено
    const STATUS_EXPIRED = 'expired'; // просрочено

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['author_id', 'category_id', 'price', 'executor_id'], 'integer'],
            [['description'], 'string'],
            [['date_start', 'date_end'], 'safe'],
            [['title', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author_id' => 'Author ID',
            'title' => 'Title',
            'description' => 'Description',
            'category_id' => 'Category ID',
            'price' => 'Price',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
            'executor_id' => 'Executor ID',
            'status' => 'Status',
        ];
    }

    public function getStatusList(): array
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

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getAuthor()
    {
        return $this->hasOne(UserData::class, ['user_id' => 'author_id']);
    }

    public function getReviewsCount()
    {
        return $this->hasOne(Review::class, ['author_id' => 'id']);
    }

    public function getFiles()
    {
        return $this->hasMany(TaskFile::class, ['task_id' => 'id'])->asArray();
    }

    public function getResponds()
    {
        return $this->hasMany(TaskRespond::class, ['task_id' => 'id']);
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getRole(int $userId): string
    {
        return [
                $this->authorId => self::ROLE_OWNER,
                $this->executorId => self::ROLE_EXECUTOR,
            ][$userId] ?? '';
    }
}
