<?php

namespace app\models;

use common\models\User;
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
            ['user_id', 'required'],
            [
                ['user_id', 'views', 'success_counter', 'failing_counter'],
                'integer',
            ],
            [
                'user_id',
                'exist',
                'targetClass' => User::class,
                'targetAttribute' => 'id',
            ],
            ['description', 'string'],
            ['phone', 'string', 'length' => [11, 11]],
            ['skype', 'match', 'pattern' => '/^[0-9a-zA-Z]{3,}$/'],
            [
                ['other_messenger', 'avatar'],
                'string',
                'max' => 255,
            ],
        ];
    }
}
