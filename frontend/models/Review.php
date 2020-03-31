<?php

namespace app\models;

use common\models\User;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\ServerErrorHttpException;

/**
 * Класс для работы с моделью отзывов
 *
 * Class Review
 *
 * @package app\models
 */
class Review extends ActiveRecord
{
    /**
     * Создание связи с автором отклика
     *
     * @return ActiveQuery
     */
    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'author_id']);
    }

    /**
     * Создание связи с заданием к которому был сделан отклик
     *
     * @return ActiveQuery
     */
    public function getTask(): ActiveQuery
    {
        return $this->hasOne(Task::class, ['id' => 'task_id']);
    }

    /**
     * Получение имени таблицы модели
     *
     * @return string имя таблицы модели
     */
    public static function tableName(): string
    {
        return 'review';
    }

    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     */
    public function rules(): array
    {
        return [
            [['task_id', 'author_id', 'executor_id', 'text'], 'required'],
            [['task_id', 'author_id', 'executor_id'], 'integer'],
            [['text'], 'string'],
        ];
    }

    /**
     * Переопределение метода сохранения данных что бы при неудаче выбрасывалась ошибка
     *
     * @param bool $runValidation
     * @param null $attributeNames
     *
     * @return bool|void
     * @throws ServerErrorHttpException
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if (!parent::save($runValidation, $attributeNames)) {
            throw new ServerErrorHttpException();
        }
    }
}
