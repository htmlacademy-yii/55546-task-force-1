<?php

namespace app\models;

use common\models\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Класс для работы с моделью ленты событий
 *
 * Class EventRibbon
 *
 * @package app\models
 */
class EventRibbon extends ActiveRecord
{
    /** @var string строка со статусом нового отлика на задание */
    public const TYPE_NEW_TASK_RESPOND = 'new-task-respond';
    /** @var string строка со статусом нового сообщения */
    public const TYPE_NEW_CHAT_MESSAGE = 'new-chat-message';
    /** @var string строка со статусом отенённого задания */
    public const TYPE_TASK_DENIAL = 'task-denial';
    /** @var string строка со статусом начала задания */
    public const TYPE_TASK_START = 'task-start';
    /** @var string строка со статусом завершения задания */
    public const TYPE_TASK_COMPLETE = 'task-complete';

    /**
     * Создание связи с заданием для данного события
     *
     * @return ActiveQuery
     */
    public function getTask(): ActiveQuery
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }

    /**
     * Получение имени таблицы модели
     *
     * @return string имя таблицы модели
     */
    public static function tableName(): string
    {
        return 'event_ribbon';
    }

    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     */
    public function rules(): array
    {
        return [
            [['user_id', 'task_id', 'type', 'message'], 'required'],
            [['user_id', 'task_id'], 'integer'],
            [
                'user_id',
                'exist',
                'targetClass' => User::class,
                'targetAttribute' => 'id',
            ],
            [
                'task_id',
                'exist',
                'targetClass' => Task::class,
                'targetAttribute' => 'id',
            ],
            ['type', 'string', 'length' => [0, 255]],
            ['message', 'string'],
        ];
    }
}
