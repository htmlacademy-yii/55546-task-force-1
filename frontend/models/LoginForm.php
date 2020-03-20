<?php
namespace app\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Класс для работы с моделью формы авторизации
 *
 * Class LoginForm
 *
 * @package app\models
 */
class LoginForm extends Model
{
    /** @var string строка с почтовым адресом пользователя */
    public $email;
    /** @var string строка с паролем пользователя */
    public $password;

    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     */
    public function rules(): array
    {
        return [
            ['email', 'required', 'message' => 'Поле должно быть заполнено'],
            ['email', 'email'],
            ['email', 'exist', 'targetClass' => 'common\models\User'],
            ['password', 'required'],
            ['password', 'checkPassword'],
        ];
    }

    /**
     * Указание списка имён для атрибутов формы
     *
     * @return array список имён для атрибутов формы
     */
    public function attributeLabels(): array
    {
        return [
            'email' => 'Email',
            'password' => 'Пароль',
        ];
    }

    /**
     * Валидатор для проверки правильности пароля для конкретного пользователя
     */
    public function checkPassword(): void
    {
        $user = User::findOne(['email' => $this->email]);
        if(!$user || !Yii::$app->getSecurity()->validatePassword($this->password, $user->password)) {
            $this->addError('password', "Не верный пароль");
        }
    }
}
