<?php
namespace app\models;

use yii\base\Model;

class MainLoginForm extends Model
{
    public $email;
    public $password;

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
        ];
    }

    public function loginValidate()
    {
        $user = User::findOne(['email' => $this->email]);
        if(!$user) {
            $this->addError('email', 'Пользователь с указанным Email не найден');
        } else if($user->password !== $this->password) {
            $this->addError('password', 'Не верный пароль');
        }

        return $this;
    }
}
