<?php

namespace app\models;

use common\models\User;
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
    /** @var string строка с идентификатором города пользователя */
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
            [['login', 'email', 'password', 'cityId'], 'required'],
            [['login', 'email'], 'trim'],
            ['login', 'string', 'min' => 2, 'max' => 255],
            [
                'login',
                'unique',
                'targetClass' => User::class,
                'message' => 'Данное имя уже занято',
            ],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [
                'email',
                'unique',
                'targetClass' => User::class,
                'message' => 'Данный email уже используется',
            ],
            ['password', 'string', 'min' => 6],
            ['cityId', 'integer'],
            [
                'cityId',
                'exist',
                'targetClass' => City::class,
                'targetAttribute' => 'id',
                'message' => 'Указанный город не найден в нашей базе данных',
            ],
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
