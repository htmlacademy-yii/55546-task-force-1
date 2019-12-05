<?php

namespace app\models;

use Yii;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $login
 * @property string|null $email
 * @property string|null $password
 * @property string|null $date_registration
 */
class User extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_registration'], 'safe'],
            [['login', 'email', 'password'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'email' => 'Email',
            'password' => 'Password',
            'date_registration' => 'Date Registration',
        ];
    }

    public function getReviews(): array
    {
        return [];
        /*
            todo: получить список отзывов по данному пользователю
        */
    }

    public function getDataProfile(): array
    {
        return [];
        /*
            todo: получить данные для профиля пользователя
        */
    }

    public function getDataSettings(): array
    {
        return [];
        /*
            todo: получить данные настроек пользователя
        */
    }

    public function getChat(): array
    {
        return [];
        /*
            todo: получить чат активный для данного пользователя
        */
    }

    public function getTasks(): array
    {
        return [];
        /*
            todo: получить список задач созданных данным пользователем
        */
    }

    public static function getDataExecutorsList(): array
    {
        return [];
        /*
            todo: получить данные для списка исполнителей
        */
    }
}
