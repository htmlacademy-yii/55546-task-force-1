<?php
namespace app\models;

use frontend\components\DebugHelper\DebugHelper;
use frontend\components\YandexMap\YandexMap;
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

    public function rules(): array
    {
        return [
            [['title', 'description', 'categoryId'], 'required', 'message' => 'Поле должно быть заполнено'],
            [['categoryId', 'price'], 'integer', 'message' => 'Это поле может быть только целым числом'],
            ['categoryId', 'checkCategory'],
            [['description', 'location'], 'string'],
            ['location', 'checkLocation'],
            ['price', 'number', 'min' => 1, 'message' => 'Цена должна быть больше нуля'],
            ['dateEnd', 'match', 'pattern' => '/^\d{4}-\d{2}-\d{2}$/', 'message' => 'Не корректный формат даты'],
            ['dateEnd', 'checkDate']
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

    public function checkDate(): void
    {
        if(time() > strtotime($this->dateEnd)) {
            $this->addError('dateEnd', 'Дата завершение задачи должна быть после текущей');
        }
    }

    public function checkLocation(): void
    {
        if(!empty($this->location)) {
            $geocode = YandexMap::getPosition($this->location);

            if(!$geocode) {
                $this->addError('location', 'Указанная локация не определена');
            } else {
                $geocode = explode(' ', $geocode);
                $this->longitude = $geocode[0];
                $this->latitude = $geocode[1];
            }
        }
    }

    public function create(Task $task, string $status): bool
    {
        $task->author_id = Yii::$app->user->getId();
        $task->title = $this->title;
        $task->description = $this->description;
        $task->category_id = $this->categoryId;
        $task->price = $this->price;
        $task->latitude = $this->latitude;
        $task->longitude = $this->longitude;
        $task->date_end = $this->dateEnd;
        $task->date_start = date("Y-m-d h:i:s");
        $task->status = $status;

        return $task->save();
    }
}
