<?php
namespace app\models;

use common\models\User;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Класс для работы с моделью формы настроек пользователя
 *
 * Class SettingsForm
 *
 * @package app\models
 */
class SettingsForm extends Model
{
    /** @var UploadedFile[] массив со списком фотографий работ пользователя */
    public $files;
    /** @var UploadedFile картинка с аватаром пользователя */
    public $avatar;
    /** @var string строка с именем пользователя */
    public $name;
    /** @var string строка с почтовым ящиком пользователя */
    public $email;
    /** @var string строка с идентификатором города пользователя */
    public $cityId;
    /** @var string строка с днём рождения пользователя */
    public $birthday;
    /** @var string строка с описанием пользователя */
    public $description;
    /** @var array массив со списком специализаций пользователя */
    public $specializations = [];
    /** @var string строка с паролем пользователя */
    public $password;
    /** @var string строка с копией пароля пользователя */
    public $copyPassword;
    /** @var string строка с телефоном пользователя */
    public $phone;
    /** @var string строка со скайпом пользователя */
    public $skype;
    /** @var string строка с другим мессенджером пользователя */
    public $otherMessenger;
    /** @var array массив со списком уведомлений пользователя */
    public $notifications = [
        'new-message' => false,
        'task-actions' => false,
        'new-review' => false,
        'show-only-client' => false,
        'hidden-profile' => false,
    ];
    /** @var array массив со списком настроек пользователя */
    public $settings = [
        'show-only-client' => false,
        'hidden-profile' => false,
    ];

    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     */
    public function rules(): array
    {
        return [
            [['avatar', 'files', 'name', 'email', 'cityId', 'birthday', 'description', 'specializations', 'password', 'copyPassword', 'phone', 'skype', 'otherMessenger', 'notifications', 'settings'], 'safe'],
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
            ['description', 'string'],
            ['phone', 'string', 'length' => [11, 11]],
            ['otherMessenger', 'trim'],
            ['otherMessenger', 'string', 'min' => 1],
            ['skype', 'match', 'pattern' => '/^[0-9a-zA-Z]{3,}$/'],
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

    /**
     * Валидатор для проверки доступности имени пользователя,
     * при условии что это имя занято не им же
     */
    public function validateName(): void
    {
        if((Yii::$app->user->identity->login !== $this->name) && User::findOne(['login' => $this->name])) {
            $this->addError('login', 'Выбранное имя уже занято');
        }
    }

    /**
     * Валидатор для проверки доступности почтового ящика пользователя,
     * при условии что этот почтовый ящик занят не им же
     */
    public function validateEmail(): void
    {
        if((Yii::$app->user->identity->email !== $this->email) && User::findOne(['email' => $this->email])) {
            $this->addError('email', 'Указанный email уже используется');
        }
    }

    /**
     * Валидатор для проверки доступности выбранного города в базе данных сайта
     */
    public function validateCity(): void
    {
        if(!City::findOne((int) $this->cityId)) {
            $this->addError('email', 'Город с указанным id не найден');
        }
    }

    /**
     * Валидатор для проверки дня рождения пользователя
     */
    public function validateBirthday(): void
    {
        if(strtotime($this->birthday) >= time()) {
            $this->addError('birthday', 'День рождения должен быть датой прошедшего времени');
        }
    }

    /**
     * Валидатор для проверки доступности выбранных специализаций в базе данных сайта
     */
    public function validateSpecializations(): void
    {
        if((int) Category::find()->where(['id' => $this->specializations])->count() !== count($this->specializations)) {
            $this->addError('specializations', 'Одна или несколько из выбранных вами специализаций не найдена');
        }
    }
}
