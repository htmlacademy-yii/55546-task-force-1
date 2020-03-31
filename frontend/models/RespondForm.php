<?php

namespace app\models;

use yii\base\Model;

/**
 * Класс для работы с моделью формы отклика к заданию
 *
 * Class RespondForm
 *
 * @package app\models
 */
class RespondForm extends Model
{
    /** @var integer число с ценой за работу */
    public $price;
    /** @var string строка с описанием к отклику */
    public $text;

    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     */
    public function rules(): array
    {
        return [
            ['price', 'required', 'message' => 'Поле должно быть заполнено'],
            [
                'price',
                'integer',
                'min' => 1,
                'message' => 'Цена должна быть больше нуля',
            ],
            ['text', 'string'],
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
            'price' => 'Ваша цена',
            'text' => 'Комментарий',
        ];
    }
}
