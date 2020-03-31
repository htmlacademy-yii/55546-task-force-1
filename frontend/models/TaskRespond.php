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
    const STATUS_NEW = 'new';
    /** @var string строка со статусом принятого отклика */
    const STATUS_ACCEPTED = 'accepted';
    /** @var string строка со статусом отклонённого отклика */
    const STATUS_DENIED = 'denied';

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
}
