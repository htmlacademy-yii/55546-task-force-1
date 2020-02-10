<?php
namespace app\models;

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

    public function rules(): array
    {

        return [
            [['title', 'description', 'categoryId'], 'required', 'message' => 'Поле должно быть заполнено'],
            [['categoryId', 'price'], 'integer', 'message' => 'Это поле может быть только целым числом'],
            ['categoryId', 'checkCategory'],
            [['description'], 'string'],
            ['price', 'number', 'min' => 1, 'message' => 'Цена должна быть больше нуля'],
            ['dateEnd', 'match', 'pattern' => '/^\d{4}-\d{2}-\d{2}$/', 'message' => 'Не корректный формат даты']
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

    public function create(Task $task, string $status): bool
    {
        $task->author_id = Yii::$app->user->getId();
        $task->title = $this->title;
        $task->description = $this->description;
        $task->category_id = $this->categoryId;
        $task->price = $this->price;
        $task->location = $this->location;

        if(isset($this->date_end)) {
            $task->date_end = strtotime($this->date_end);
        }

        $task->status = $status;

        return $task->save();
    }
}
