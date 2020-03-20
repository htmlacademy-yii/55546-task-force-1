<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Класс для работы с моделью категорий
 *
 * Class Category
 *
 * @package app\models
 */
class Category extends ActiveRecord
{
    /**
     * Получение списка всех категорий отформатированных в массивы с парами id => title
     *
     * @return array список всех категорий отформатированных в массивы с парами id => title
     */
    public static function getCategoriesArray(): array
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'title');
    }

    /**
     * Получение имени таблицы модели
     *
     * @return string имя таблицы модели
     */
    public static function tableName(): string
    {
        return 'category';
    }

    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     */
    public function rules(): array
    {
        return [
            [['title', 'code'], 'string', 'max' => 255],
        ];
    }
}
