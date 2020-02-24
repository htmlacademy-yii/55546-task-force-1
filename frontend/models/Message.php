<?php
namespace app\models;

use yii\db\ActiveRecord;

class Message extends ActiveRecord
{
    public function rules()
    {
        return [
            [['task_id', 'message', 'is_mine'], 'safe'],
            [['task_id', 'message'], 'required', 'message' => 'Поле должно быть заполнено'],
            ['task_id', 'integer', 'min' => 1, 'message' => 'id задание должно быть числом больше нуля'],
            ['task_id', 'exist', 'targetClass' => Task::class, 'targetAttribute' => ['task_id' => 'id']],
            ['message', 'string'],
            ['is_mine', 'boolean'],
        ];
    }

    public static function tableName()
    {
        return 'message';
    }
}
