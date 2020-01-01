<?php

namespace app\models;

use yii\base\Model;

class TasksFilter extends Model
{
    public $category;
    public $isNoExecutor;
    public $isTelework;
    public $title;
    public $time;
    private $taskQuery;

    public function __construct($data, $taskQuery)
    {
        parent::__construct();

        $this->category = $data['category'];
        $this->isNoExecutor = $data['is-no-executor'];
        $this->isTelework = $data['is-telework'];
        $this->title = $data['title'];
        $this->time = $data['time'];

        $this->taskQuery = $taskQuery;
    }

    public function applyFilters() {
        if(isset($this->category)) {
            $this->taskQuery->andWhere("`category_id` IN (" . implode(',', array_keys($this->category)) . ")");
        }
        if(isset($this->isNoExecutor)) {
            $this->taskQuery->andWhere("`executor_id` IS NULL");
        }
        if(isset($this->isTelework)) {
            $this->taskQuery->andWhere(['is_telework' => true]);
        }
        if(isset($this->title)) {
            $this->taskQuery->andWhere(['like', 'title', $this->title]);
        }

        return $this->taskQuery;
    }
}
