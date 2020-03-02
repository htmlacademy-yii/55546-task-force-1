<?php
namespace app\models;

use frontend\components\DebugHelper;
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
            ['login', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This login has already been taken.'],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['cityId', 'required'],
            ['cityId', 'integer'],
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
}
