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

    public function getDescription()
    {
        return [
            self::TYPE_NEW_TASK_RESPOND => 'Новый отклик к заданию',
            self::TYPE_NEW_CHAT_MESSAGE => 'Новое сообщение в чате',
            self::TYPE_TASK_DENIAL => 'Исполнитель отказался от задания',
            self::TYPE_TASK_START => 'Ваш отклик был принят',
            self::TYPE_TASK_COMPLETE => 'Завершено задание',
        ][$this->type] ?? 'err';
    }

    public function getIconClass()
    {
        $class = 'lightbulb__new-task--executor';
        if ($this->type === self::TYPE_NEW_CHAT_MESSAGE) {
            $class = 'lightbulb__new-task--message';
        } elseif($this->type === self::TYPE_TASK_COMPLETE) {
            $class = 'lightbulb__new-task--close';
        }

        return $class;
    }

    public function getTask()
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }
}
