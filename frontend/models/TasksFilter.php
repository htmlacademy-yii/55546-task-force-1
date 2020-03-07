<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveQuery;
use yii\db\Expression;

class TasksFilter extends Model
{
    public $category = [];
    public $isNoExecutor;
    public $isTelework;
    public $title;
    public $time = 'day';

    const PERIOD_LIST = [
        'day' => 'За день',
        'week' => 'За неделю',
        'month' => 'За месяц'
    ];

    public function rules()
    {
        return [
            [['category', 'isNoExecutor', 'isTelework', 'title', 'time'], 'safe'],
        ];
    }

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
        if(!empty($this->category)) {
            $taskQuery->andWhere(['category_id' => $this->category]);
        }

        if($this->isNoExecutor) {
            $taskQuery->andWhere(['executor_id' => null]);
        }

        if(!$this->isTelework) {
            $taskQuery->andWhere([
                'city_id' => Yii::$app->session->get('city') ?
                    Yii::$app->session->get('city') : Yii::$app->user->identity->city_id
            ]);
        }

        if(isset($this->title)) {
            $taskQuery->andWhere(['like', 'title', $this->title]);
        }

        if(isset($this->time) && in_array($this->time, array_keys(self::PERIOD_LIST))) {
            $taskQuery->andWhere("date_start > CURRENT_TIMESTAMP() - INTERVAL 1 $this->time");
        }
    }
}
