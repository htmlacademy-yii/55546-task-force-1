<?php
namespace app\models;

use frontend\components\DebugHelper\DebugHelper;
use Yii;
use yii\base\Model;
use common\models\User;
use yii\db\Exception;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $login;
    public $email;
    public $cityId;
    public $password;

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
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['cityId', 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Электронная почта',
            'login' => 'Ваше имя',
            'cityId' => 'Город проживания',
            'password' => 'Пароль',
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup(): User
    {
        if (!$this->validate()) {
            throw new \Exception();
        }

        $user = new User();
        $user->login = $this->login;
        $user->email = $this->email;
        $user->city_id = $this->cityId;
        $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);

        if (!$user->save()) {
            throw new \Exception();
        }

        return $user;
    }
}
