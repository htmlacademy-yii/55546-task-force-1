<?php

namespace app\models;

use frontend\components\YandexMap\YandexMap;
use Yii;
use yii\db\ActiveRecord;
use app\models\{Category, UserData, Review, TaskFile, TaskRespond};
use common\models\User;
use yii\helpers\Url;

class Task extends ActiveRecord
{
    const STATUS_NEW = 'new'; // новое
    const STATUS_EXECUTION = 'execution'; // выполняется
    const STATUS_COMPLETED = 'completed'; // завершено
    const STATUS_CANCELED = 'canceled'; // отменено
    const STATUS_FAILING = 'failing'; // провалено
    const STATUS_EXPIRED = 'expired'; // просрочено

    public static function tableName()
    {
        return 'task';
    }

    public function rules()
    {
        return [
            [['title', 'description', 'category_id'], 'required'],
            [['author_id', 'category_id', 'price', 'executor_id'], 'integer'],
            [['description'], 'string'],
            [['date_start', 'date_end'], 'safe'],
            [['title', 'status'], 'string', 'max' => 255],
        ];
    }

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

    public function initLocation()
    {
        $position = $this->location;
        $this->location = YandexMap::getAddressByPositions($this->location);
        $this->location->position = $position;
    }

    public function getCurrentTaskUrl(): string
    {
        return Url::to("/tasks/view/$this->id");
    }

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getAuthor()
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    public function getReviews()
    {
        return $this->hasMany(Review::class, ['author_id' => 'id']);
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

    public static function getBaseTasksUrl(): string
    {
        return Url::to("/tasks");
    }
}
