<?php
namespace app\models;

use frontend\components\DebugHelper\DebugHelper;
use Yii;
use yii\base\Model;

class TaskCreate extends Model
{
    public $title;
    public $description;
    public $categoryId;
    public $files;
    public $location;
    public $price;
    public $dateEnd;
    public $latitude;
    public $longitude;
    public $cityId;

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

    public function checkCategory(): void
    {
        if(!Category::findOne((int) $this->categoryId)) {
            $this->addError('categoryId', 'Указанной категории не существует');
        }
    }

    public function checkCity(): void
    {
        $city = City::findOne(['name' => explode(',', $this->location)[0]]);
        if(!$city) {
            $this->addError('location', 'Указанный город не найден в нашей базе данных');
        } else {
            $this->cityId = $city->id;
        }
    }

    public function checkDate(): void
    {
        if(time() > strtotime($this->dateEnd)) {
            $this->addError('dateEnd', 'Дата завершение задачи должна быть после текущей');
        }
    }

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
