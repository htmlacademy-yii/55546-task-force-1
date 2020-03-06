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
}
