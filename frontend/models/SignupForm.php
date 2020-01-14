<?php
namespace app\models;

use frontend\components\DebugHelper\DebugHelper;
use Yii;
use yii\base\Model;
use app\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $login;
    public $email;
    public $city_id;
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
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['city_id', 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Электронная почта',
            'login' => 'Ваше имя',
            'city_id' => 'Город проживания',
            'password' => 'Пароль',
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->login = $this->login;
        $user->email = $this->email;
        $user->city_id = $this->city_id;
        $user->password = $this->password;

        return $user->save();

//        $user->setPassword($this->password);
//        $user->generateAuthKey();
//        $user->generateEmailVerificationToken();

//        return $user->save() && $this->sendEmail($user);
    }

//    /**
//     * Sends confirmation email to user
//     * @param User $user user model to with email should be send
//     * @return bool whether the email was sent
//     */
//    protected function sendEmail($user)
//    {
//        return Yii::$app
//            ->mailer
//            ->compose(
//                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
//                ['user' => $user]
//            )
//            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
//            ->setTo($this->email)
//            ->setSubject('Account registration at ' . Yii::$app->name)
//            ->send();
//    }
}
