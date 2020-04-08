<?php

namespace app\models;

use yii\base\Model;

/**
 * Класс для работы с моделью формы завершения задания
 *
 * Class TaskCompletionForm
 *
 * @package app\models
 */
class TaskCompletionForm extends Model
{
    /** @var string строка со статусом исполнения задания - возниклик проблемы */
    const STATUS_DIFFICULT = 'difficult';
    /** @var string строка со статусом исполнения задания - нет проблемы */
    const STATUS_YES = 'yes';

    /** @var string строка со статусом исполнения задания выбранным заказчиком */
    public $isCompletion;
    /** @var string строка с рейтингом качества исполнения */
    public $rating;
    /** @var string строка с описанием по поводу выполненного задания */
    public $text;

    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     */
    public function rules(): array
    {
        return [
            [['rating', 'isCompletion'], 'required'],
            [
                'rating',
                'filter',
                'filter' => function ($rating) {
                    return (int)$rating;
                },
            ],
            ['rating', 'integer', 'min' => 1, 'max' => 5],
            [
                'isCompletion',
                'in',
                'range' => [self::STATUS_YES, self::STATUS_DIFFICULT],
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
            'rating' => 'Оценка',
            'text' => 'Комментарий',
        ];
    }
}
