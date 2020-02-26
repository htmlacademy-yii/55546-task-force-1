<?php
namespace app\models;

use Yii;
use yii\base\Model;

class AccountForm extends Model
{
    public $avatar;
    public $name;
    public $email;
    public $city;
    public $birthday;
    public $description;
    public $specializations = [];
    public $password;
    public $copyPassword;
    public $phone;
    public $skype;
    public $otherMessenger;
    public $notifications = [];

    public function rules()
    {
        return [
            [['avatar', 'name', 'email', 'city', 'birthday', 'description', 'specializations', 'password', 'copyPassword', 'phone', 'skype', 'otherMessenger', 'notifications'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'avatar' => 'Сменить аватар',
            'name' => 'Ваше имя',
            'email' => 'email',
            'city' => 'Город',
            'birthday' => 'День рождения',
            'description' => 'Информация о себе',
            'specializations' => 'specializations',
            'password' => 'Новый пароль',
            'copyPassword' => 'Повтор пароля',
            'phone' => 'Телефон',
            'skype' => 'skype',
            'otherMessenger' => 'Другой мессенджер',
            'notifications' => 'notifications',
        ];
    }
}
