<?php

namespace app\models;

use yii\base\Model;

/**
 * Класс для работы с моделью формы регистрации
 *
 * Class SignupForm
 *
 * @package app\models
 */
class SignupForm extends Model
{
    /** @var string строка с именем пользователя */
    public $login;
    /** @var string строка с почтовым ящиком пользователя */
    public $email;
    /** @var string строка с идентификатором пользователя */
    public $cityId;
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
            ['login', 'trim'],
            ['login', 'required'],
            ['login', 'string', 'min' => 2, 'max' => 255],
            [
                'login',
                'unique',
                'targetClass' => '\common\models\User',
                'message' => 'This login has already been taken.',
            ],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [
                'email',
                'unique',
                'targetClass' => '\common\models\User',
                'message' => 'This email address has already been taken.',
            ],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['cityId', 'required'],
            ['cityId', 'integer'],
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
            'email' => 'Электронная почта',
            'login' => 'Ваше имя',
            'cityId' => 'Город проживания',
            'password' => 'Пароль',
        ];
    }
}
