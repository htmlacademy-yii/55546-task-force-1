<?php
namespace app\models;

use common\models\User;
use Yii;
use yii\base\Model;

class SettingsForm extends Model
{
    public $avatar;
    public $name;
    public $email;
    public $cityId;
    public $birthday;
    public $description;
    public $specializations = [];
    public $password;
    public $copyPassword;
    public $phone;
    public $skype;
    public $otherMessenger;
    public $notifications = [
        'new-message' => false,
        'task-actions' => false,
        'new-review' => false,
        'show-only-client' => false,
        'hidden-profile' => false,
    ];
    public $settings = [
        'show-only-client' => false,
        'hidden-profile' => false,
    ];

    public function rules()
    {
        return [
            [['avatar', 'name', 'email', 'cityId', 'birthday', 'description', 'specializations', 'password', 'copyPassword', 'phone', 'skype', 'otherMessenger', 'notifications', 'settings'], 'safe'],
            ['avatar', 'file', 'extensions' => ['png', 'jpg', 'jpeg', 'gif']],
            [['name', 'email'], 'trim'],
            [['name', 'email'], 'required', 'message' => 'Обязательное поле'],
            ['name', 'validateName'],
            ['email', 'email', 'message' => 'Не корректный тип email'],
            ['email', 'validateEmail'],
            ['birthday', 'match', 'pattern' => '/^\d{4}-\d{2}-\d{2}$/', 'message' => 'Не корректный формат даты'],
            ['birthday', 'validateBirthday'],
            ['specializations', 'validateSpecializations'],
            ['password', 'compare', 'compareAttribute' => 'copyPassword'],
            ['cityId', 'integer'],
            ['cityId', 'validateCity'],
            [['description', 'phone', 'skype', 'otherMessenger'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'avatar' => 'Сменить аватар',
            'name' => 'Ваше имя',
            'email' => 'email',
            'cityId' => 'Город',
            'birthday' => 'День рождения',
            'description' => 'Информация о себе',
            'specializations' => 'specializations',
            'password' => 'Новый пароль',
            'copyPassword' => 'Повтор пароля',
            'phone' => 'Телефон',
            'skype' => 'skype',
            'otherMessenger' => 'Другой мессенджер',
        ];
    }

    public function validateName()
    {
        if((Yii::$app->user->identity->login !== $this->name) && User::findOne(['login' => $this->name])) {
            $this->addError('login', 'Выбранное имя уже занято');
        }
    }

    public function validateEmail()
    {
        if((Yii::$app->user->identity->email !== $this->email) && User::findOne(['email' => $this->email])) {
            $this->addError('email', 'Указанный email уже используется');
        }
    }

    public function validateCity()
    {
        if(!City::findOne((int) $this->cityId)) {
            $this->addError('email', 'Город с указанным id не найден');
        }
    }

    public function validateBirthday()
    {
        if(strtotime($this->birthday) >= time()) {
            $this->addError('birthday', 'День рождения должен быть датой прошедшего времени');
        }
    }

    public function validateSpecializations()
    {
        if((int) Category::find()->where(['id' => $this->specializations])->count() !== count($this->specializations)) {
            $this->addError('specializations', 'Одна или несколько из выбранных вами специализаций не найдена');
        }
    }
}
