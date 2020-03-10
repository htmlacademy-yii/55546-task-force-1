<?php
namespace app\models;

use yii\db\ActiveRecord;

class EventRibbon extends ActiveRecord
{
    const TYPE_NEW_TASK_RESPOND = 'new-task-respond';
    const TYPE_NEW_CHAT_MESSAGE = 'new-chat-message';
    const TYPE_TASK_DENIAL = 'task-denial';
    const TYPE_TASK_START = 'task-start';
    const TYPE_TASK_COMPLETE = 'task-complete';

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
