<?php

namespace app\models;

use common\models\User;
use DateTime;
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
    private $avatar;
    /** @var string строка с именем пользователя */
    public $name;
    /** @var string строка с почтовым ящиком пользователя */
    public $email;
    /** @var string строка с идентификатором города пользователя */
    public $cityId;
    /** @var string строка с днём рождения пользователя */
    public $birthday;
    /** @var number число timestamp с днём рождения пользователя */
    public $timestampBirthday;
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
    public $notifications = [];
    /** @var array массив со списком настроек пользователя */
    public $settings = [];

    /**
     * Возвращает массив с файлами работ пользователя
     *
     * @return array|null массив с файлами работ пользователя
     */
    public function getFiles(): ?array
    {
        return $this->files;
    }

    /**
     * Устанавливает в модель формы массив с файлами работ пользователя
     */
    public function setFiles(): void
    {
        $this->files = UploadedFile::getInstancesByName('files');
    }

    /**
     * Возвращает аватарку пользователя
     *
     * @return UploadedFile|null объект аватарки пользователя
     */
    public function getAvatar(): ?UploadedFile
    {
        return $this->avatar;
    }

    /**
     * Устанавливает в модель формы аватарку пользователя
     */
    public function setAvatar(): void
    {
        $this->avatar = UploadedFile::getInstance($this, 'avatar');
    }

    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     * @throws \Exception
     */
    public function rules(): array
    {
        $today = new DateTime();

        return [
            [
                'files',
                'file',
                'extensions' => ['png', 'jpg', 'jpeg', 'gif'],
                'maxFiles' => 6,
                'message' => 'Ошибка при сохранении файлов',
            ],
            [
                ['notifications', 'settings'],
                'in',
                'range' => [true, false],
                'allowArray' => true,
            ],
            ['avatar', 'file', 'extensions' => ['png', 'jpg', 'jpeg', 'gif']],
            [['name', 'email'], 'trim'],
            [['name', 'email'], 'required', 'message' => 'Обязательное поле'],
            [
                'name',
                'unique',
                'targetClass' => User::class,
                'targetAttribute' => 'login',
                'filter' => function ($query) {
                    $query->andWhere([
                        '!=',
                        'user.login',
                        Yii::$app->user->identity->login,
                    ]);
                },
                'message' => 'Выбранное имя уже занято',
            ],
            ['email', 'email', 'message' => 'Не корректный тип email'],
            [
                'email',
                'unique',
                'targetClass' => User::class,
                'filter' => function ($query) {
                    $query->andWhere([
                        '!=',
                        'user.email',
                        Yii::$app->user->identity->email,
                    ]);
                },
                'message' => 'Указанный email уже используется',
            ],
            [['password', 'copyPassword'], 'string', 'min' => 6],
            ['password', 'compare', 'compareAttribute' => 'copyPassword'],
            [
                'birthday',
                'match',
                'pattern' => '/^\d{4}-\d{2}-\d{2}$/',
                'message' => 'Не корректный формат даты',
            ],
            [
                'birthday',
                'date',
                'format' => 'php:Y-m-d',
                'timestampAttribute' => 'timestampBirthday',
                'max' => $today->getTimestamp(),
                'maxString' => $today->format('Y-m-d'),
                'tooBig' => '{attribute} должен быть не позже {max}.',
            ],
            [
                'specializations',
                'exist',
                'targetClass' => Category::class,
                'targetAttribute' => 'id',
                'allowArray' => true,
                'message' => 'Одна или несколько из выбранных вами специализаций не найдена',
            ],
            ['cityId', 'integer'],
            [
                'cityId',
                'exist',
                'targetClass' => City::class,
                'targetAttribute' => 'id',
                'message' => 'Город с указанным id не найден',
            ],
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
}
