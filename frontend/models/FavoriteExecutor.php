<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Класс для работы с моделью избранных исполнителей
 *
 * Class FavoriteExecutor
 *
 * @package app\models
 */
class FavoriteExecutor extends ActiveRecord
{
    /**
     * Получение имени таблицы модели
     *
     * @return string имя таблицы модели
     */
    public static function tableName(): string
    {
        return 'favorite_executor';
    }
}
