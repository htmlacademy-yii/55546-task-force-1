<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $login
 * @property string|null $email
 * @property string|null $password
 * @property string|null $date_registration
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            ['login', 'trim'],
            ['login', 'required'],
            ['login', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

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

    public static function findIdentity($id)
    {
        return static::findOne((int) $id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function getUserData()
    {
        return $this->hasOne(UserData::class, ['user_id' => 'id']);
    }

    public function getCity()
    {
        return $this->hasOne(City::class, ['id' => 'city_id']);
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
