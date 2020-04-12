<?php

namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use common\models\User;

/**
 * Класс для работы с моделью откликов к заданиям
 *
 * Class TaskRespond
 *
 * @package app\models
 */
class TaskRespond extends ActiveRecord
{
    /** @var string строка со статусом нового отклика */
    public const STATUS_NEW = 'new';
    /** @var string строка со статусом принятого отклика */
    private const STATUS_ACCEPTED = 'accepted';
    /** @var string строка со статусом отклонённого отклика */
    private const STATUS_DENIED = 'denied';

    /**
     * Устанавливает статус отклику на задание - принят, или отклонён
     *
     * @param string $status статус отклика на задание
     */
    public function setStatusAccepted(string $status): void
    {
        $this->status = $status === self::STATUS_ACCEPTED
            ? self::STATUS_ACCEPTED : self::STATUS_DENIED;
        $this->save();
    }

    /**
     * Проверка, принят отклик исполнителя к заданию, или нет
     *
     * @param string $status статус с ответом автора задания
     *
     * @return bool результат проверки, принят ли отклик исполнителя
     */
    public function getIsAccepted(string $status): bool
    {
        return $status === self::STATUS_ACCEPTED;
    }

    /**
     * Создание связи с пользователем
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Получение имени таблицы модели
     *
     * @return string имя таблицы модели
     */
    public static function tableName(): string
    {
        return 'task_respond';
    }

    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     */
    public function rules(): array
    {
        return [
            [['user_id', 'task_id', 'status', 'public_date'], 'required'],
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
            ['text', 'string'],
            ['price', 'integer', 'min' => 1],
            [
                'status',
                'in',
                'range' => [
                    self::STATUS_NEW,
                    self::STATUS_ACCEPTED,
                    self::STATUS_DENIED,
                ],
            ],
            [
                'public_date',
                'match',
                'pattern' => '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/',
            ],
        ];
    }
}
