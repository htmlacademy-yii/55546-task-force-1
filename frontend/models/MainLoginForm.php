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

    public function getUser()
    {
        return User::find()->where(['email' => $this->email, 'password' => $this->password])->asArray()->one();
    }
}
