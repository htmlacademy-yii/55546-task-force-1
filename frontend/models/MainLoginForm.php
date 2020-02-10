<?php
namespace app\models;

use yii\base\Model;
use common\models\User;

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

    public function loginValidate(array $data): User
    {
        $user = User::findOne(['email' => $data['email']]);
        if(empty($user)) {
            $this->addError('email', "Пользователь с указанным Email {$this->email} не найден");
        } else if(!\Yii::$app->getSecurity()->validatePassword($data['password'], $user->password)) {
            $this->addError('password', "Не верный пароль");
        }

        return $user;
    }
}
