<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

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
    const TYPE_NEW_TASK_RESPOND = 'new-task-respond';
    /** @var string строка со статусом нового сообщения */
    const TYPE_NEW_CHAT_MESSAGE = 'new-chat-message';
    /** @var string строка со статусом отенённого задания */
    const TYPE_TASK_DENIAL = 'task-denial';
    /** @var string строка со статусом начала задания */
    const TYPE_TASK_START = 'task-start';
    /** @var string строка со статусом завершения задания */
    const TYPE_TASK_COMPLETE = 'task-complete';

    /**
     * Получение строки с описанием события в соответствии с его типом
     *
     * @return string строка с описанием события
     */
    public function getDescription(): string
    {
        return ArrayHelper::getValue([
            self::TYPE_NEW_TASK_RESPOND => 'Новый отклик к заданию',
            self::TYPE_NEW_CHAT_MESSAGE => 'Новое сообщение в чате',
            self::TYPE_TASK_DENIAL => 'Исполнитель отказался от задания',
            self::TYPE_TASK_START => 'Ваш отклик был принят',
            self::TYPE_TASK_COMPLETE => 'Завершено задание',
        ], $this->type, 'Не определённое действие');
    }

    /**
     * Получение строки CSS класса для формирования иконки соответствующей типу события
     *
     * @return string строка CSS класса
     */
    public function getIconClass(): string
    {
        $class = 'lightbulb__new-task--executor';
        if ($this->type === self::TYPE_NEW_CHAT_MESSAGE) {
            $class = 'lightbulb__new-task--message';
        } elseif ($this->type === self::TYPE_TASK_COMPLETE) {
            $class = 'lightbulb__new-task--close';
        }

        return $class;
    }

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
            [['user_id', 'task_id', 'type', 'message'], 'safe'],
        ];
    }
}
