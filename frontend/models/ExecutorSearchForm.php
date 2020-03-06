<?php
namespace app\models;

use yii\base\Model;

class ExecutorSearchForm extends Model
{
    public $categories = [];
    public $additionally = [
        'now-free',
        'now-online',
        'there-are-reviews',
        'in-favorites',
    ];
    public $name;

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
}
