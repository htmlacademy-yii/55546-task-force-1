<?php
namespace app\models;

use frontend\components\DebugHelper\DebugHelper;
use yii\base\Model;
use yii\db\ActiveQuery;

class ExecutorSearchForm extends Model
{
    public $categories = [];
    public $additionally = [
        'now-free',
        'now-online',
        'there-are-reviews',
        'in-favorites',
    ];
    public $name = '';

    public function attributeLabels()
    {
        return [
            'name' => 'Поиск по имени',
        ];
    }

    public function rules()
    {
        return [
            ['name', 'string']
        ];
    }

    public function applyFilters(ActiveQuery &$executorQuery)
    {
        if ($this->additionally) {
            if (in_array('now-online', $this->additionally)) {
                $executorQuery->andWhere("last_activity > CURRENT_TIMESTAMP() - INTERVAL 30 minute");
            }
        }
    }
}
