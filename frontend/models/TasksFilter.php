<?php

namespace app\models;

use yii\base\Model;
use yii\db\ActiveQuery;

class TasksFilter extends Model
{
    public $category;
    public $isNoExecutor;
    public $isTelework;
    public $title;
    public $time;

    public function attributeLabels()
    {
        return [
            'category' => 'Категори',
            'isNoExecutor' => 'Без исполнителя',
            'isTelework' => 'Удаленная работа',
            'title' => 'Поиск по названию',
            'time' => 'Период',
        ];
    }

    public function applyFilters(ActiveQuery &$taskQuery) {
        if(isset($this->category)) {
            $taskQuery->andWhere("`category_id` IN (" . implode(',', array_keys($this->category)) . ")");
        }
        if($this->isNoExecutor === 'on') {
            $taskQuery->andWhere("`executor_id` IS NULL");
        }
        if($this->isTelework === 'on') {
            $taskQuery->andWhere(['is_telework' => true]);
        }
        if(isset($this->title)) {
            $taskQuery->andWhere(['like', 'title', $this->title]);
        }

        return $taskQuery;
    }
}
