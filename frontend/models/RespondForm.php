<?php
namespace app\models;

use yii\base\Model;

class RespondForm extends Model
{
    public $price;
    public $text;

    public function rules()
    {
        return [
            ['price', 'required', 'message' => 'Поле должно быть заполнено'],
            ['price', 'integer', 'min' => 1, 'message' => 'Цена должна быть больше нуля'],
            ['text', 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'price' => 'Ваша цена',
            'text' => 'Комментарий'
        ];
    }

    public function createRespond($userId, $taskId)
    {
        $taskRespond = new TaskRespond();
        $taskRespond->user_id = $userId;
        $taskRespond->task_id = $taskId;
        $taskRespond->text = $this->text;
        $taskRespond->price = $this->price;
        $taskRespond->status = TaskRespond::STATUS_NEW;
        $taskRespond->save();
    }
}
