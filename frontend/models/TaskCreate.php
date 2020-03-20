<?php
namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Класс для работы с моделью формы создания задания
 *
 * Class TaskCreate
 *
 * @package app\models
 */
class TaskCreate extends Model
{
    /** @var string строка с названием задания */
    public $title;
    /** @var string строка с описанием задания */
    public $description;
    /** @var string строка с идентификатором категории */
    public $categoryId;
    /** @var array массив со списком файлов к заданию */
    public $files;
    /** @var string строка с локацией задания */
    public $location;
    /** @var string строка с наградой за задание */
    public $price;
    /** @var string строка с датой завершения */
    public $dateEnd;
    /** @var string строка с координатами широты */
    public $latitude;
    /** @var string строка с координатами долготы */
    public $longitude;
    /** @var string строка с идентификатором города */
    public $cityId;

    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     */
    public function rules(): array
    {
        return [
            [['title', 'description', 'categoryId', 'location', 'price', 'dateEnd', 'cityId', 'files'], 'safe'],
            [['title', 'description'], 'trim'],
            [['title', 'description', 'categoryId'], 'required', 'message' => 'Поле должно быть заполнено'],
            ['title', 'string', 'min' => 10],
            ['description', 'string', 'min' => 30],
            [['categoryId', 'price'], 'integer', 'message' => 'Это поле может быть только целым числом'],
            ['categoryId', 'checkCategory'],
            [['description', 'location'], 'string'],
            ['location', 'checkCity'],
            ['location', 'checkLocation'],
            ['price', 'number', 'min' => 1, 'message' => 'Цена должна быть больше нуля'],
            ['dateEnd', 'match', 'pattern' => '/^\d{4}-\d{2}-\d{2}$/', 'message' => 'Не корректный формат даты'],
            ['dateEnd', 'checkDate'],
            ['cityId', 'checkCity']
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
            'title' => 'Мне нужно',
            'description' => 'Подробности задания',
            'categoryId' => 'Категория',
            'files' => 'Файлы',
            'location' => 'Локация',
            'price' => 'Бюджет',
            'dateEnd' => 'Срок исполнения',
        ];
    }

    /**
     * Валидатор для проверки существование указанной категории в базе данных сайта
     */
    public function checkCategory(): void
    {
        if(!Category::findOne((int) $this->categoryId)) {
            $this->addError('categoryId', 'Указанной категории не существует');
        }
    }

    /**
     * Валидатор для проверки существование указанного города в базе данных сайта
     */
    public function checkCity(): void
    {
        $city = City::findOne(['name' => explode(',', $this->location)[0]]);
        if(!$city) {
            $this->addError('location', 'Указанный город не найден в нашей базе данных');
        } else {
            $this->cityId = $city->id;
        }
    }

    /**
     * Валидатор для проверки даты завершения задания
     */
    public function checkDate(): void
    {
        if(time() > strtotime($this->dateEnd)) {
            $this->addError('dateEnd', 'Дата завершение задачи должна быть после текущей');
        }
    }

    /**
     * Валидатор для проверки локации задания через яндекс карты
     *
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function checkLocation(): void
    {
        if(!empty($this->location)) {
            $geocode = Yii::$container->get('yandexMap')->getPosition($this->location);

            if(!$geocode) {
                $this->addError('location', 'Указанная локация не определена');
            } else {
                $geocode = explode(' ', $geocode);
                $this->longitude = $geocode[0];
                $this->latitude = $geocode[1];
            }
        }
    }
}
