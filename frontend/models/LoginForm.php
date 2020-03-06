<?php
namespace app\models;

use yii\base\Model;
use common\models\User;

class LoginForm extends Model
{
    public $email;
    public $password;

    public function rules()
    {
        return [
            ['email', 'required', 'message' => 'Поле должно быть заполнено'],
            ['email', 'email'],
            ['email', 'exist', 'targetClass' => 'common\models\User'],
            ['password', 'required'],
            ['password', 'checkPassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
        ];
    }

    public function checkPassword()
    {
        $user = User::findOne(['email' => $this->email]);
        if(!$user || !\Yii::$app->getSecurity()->validatePassword($this->password, $user->password)) {
            $this->addError('password', "Не верный пароль");
        }
    }
}
