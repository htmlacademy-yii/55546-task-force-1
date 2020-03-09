<?php
namespace app\models;

use yii\db\ActiveRecord;

class EventRibbon extends ActiveRecord
{
    public static function tableName()
    {
        return 'event_ribbon';
    }

    public function rules()
    {
        return [
            [['user_id', 'task_id', 'type', 'message'], 'safe'],
        ];
    }
}
