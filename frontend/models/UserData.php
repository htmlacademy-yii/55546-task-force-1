<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * Класс для работы с моделью данных пользователя
 *
 * Class UserData
 *
 * @package app\models
 */
class UserData extends ActiveRecord
{
    /**
     * Получение обработанной ссылки на автара данного пользователя
     *
     * @return string обработанная строка ссылки на аватар
     */
    public function getAvatar(): string
    {
        if (!empty($this->avatar)) {
            return preg_match('/^http/', $this->avatar) ? $this->avatar
                : Url::to("/$this->avatar");
        }

        return Url::to('/img/user-photo.png');
    }

    /**
     * Получение имени таблицы модели
     *
     * @return string имя таблицы модели
     */
    public static function tableName(): string
    {
        return 'user_data';
    }

    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     */
    public function rules(): array
    {
        return [
            [
                [
                    'description',
                    'skype',
                    'phone',
                    'other_messenger',
                    'avatar',
                    'rating',
                    'views',
                    'success_counter',
                    'failing_counter',
                ],
                'safe',
            ],
            [
                ['user_id', 'views', 'success_counter', 'failing_counter'],
                'integer',
            ],
            ['description', 'string'],
            [
                ['skype', 'phone', 'other_messenger', 'avatar', 'rating'],
                'string',
                'max' => 255,
            ],
        ];
    }
}
