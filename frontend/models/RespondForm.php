<?php
namespace app\models;

use frontend\components\DebugHelper\DebugHelper;
use yii\base\Model;

class RespondForm extends Model
{
    public $price;
    public $text;

    public function rules()
    {
        return [
            ['price', 'required'],
            ['price', 'integer', 'min' => 1],
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
