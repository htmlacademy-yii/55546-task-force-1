<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Класс для работы с моделью сообщений
 *
 * Class Message
 *
 * @package app\models
 */
class Message extends ActiveRecord
{
    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     */
    public function rules(): array
    {
        return [
            [['task_id', 'message', 'is_mine'], 'safe'],
            [
                ['task_id', 'message'],
                'required',
                'message' => 'Поле должно быть заполнено',
            ],
            [
                'task_id',
                'integer',
                'min' => 1,
                'message' => 'id задание должно быть числом больше нуля',
            ],
            [
                'task_id',
                'exist',
                'targetClass' => Task::class,
                'targetAttribute' => ['task_id' => 'id'],
            ],
            ['message', 'string'],
            ['is_mine', 'boolean'],
        ];
    }

    /**
     * Получение имени таблицы модели
     *
     * @return string имя таблицы модели
     */
    public static function tableName(): string
    {
        return 'message';
    }
}
