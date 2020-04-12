<?php

namespace app\models;

use common\models\User;
use yii\db\ActiveRecord;

/**
 * Класс для работы с моделью специализаций пользователя
 *
 * Class UserSpecialization
 *
 * @package app\models
 */
class UserSpecialization extends ActiveRecord
{
    /**
     * Получение имени таблицы модели
     *
     * @return string имя таблицы модели
     */
    public static function tableName(): string
    {
        return 'user_specialization';
    }

    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     */
    public function rules(): array
    {
        return [
            [['user_id', 'category_id'], 'required'],
            [['user_id', 'category_id'], 'integer'],
            [
                'user_id',
                'exist',
                'targetClass' => User::class,
                'targetAttribute' => 'id',
            ],
            [
                'category_id',
                'exist',
                'targetClass' => Category::class,
                'targetAttribute' => 'id',
            ],
        ];
    }
}
