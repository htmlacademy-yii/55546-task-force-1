<?php

namespace app\models;

use common\models\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Класс для работы с моделью пользователя VK
 *
 * Class Auth
 *
 * @package app\models
 */
class Auth extends ActiveRecord
{
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
        return 'auth';
    }

    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     */
    public function rules(): array
    {
        return [
            [['user_id', 'source', 'source_id'], 'required'],
            [['user_id', 'source', 'source_id'], 'integer'],
            [
                'user_id',
                'exist',
                'targetClass' => User::class,
                'targetAttribute' => 'id',
            ],
        ];
    }
}
