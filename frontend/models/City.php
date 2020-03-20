<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Класс для работы с моделью городов
 *
 * Class City
 *
 * @package app\models
 */
class City extends ActiveRecord
{
    /**
     * Получение списка всех городов отформатированных в массивы с парами id => title
     *
     * @return array массив со списком всех городов id => title
     */
    public static function getCitiesArray(): array
    {
        return ArrayHelper::map((new Query())->from('city')
            ->select(['id', 'name'])->all(), 'id', 'name');
    }

    /**
     * Получение имени таблицы модели
     *
     * @return string имя таблицы модели
     */
    public static function tableName(): string
    {
        return 'city';
    }

    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     */
    public function rules(): array
    {
        return [
            [['lat', 'long'], 'number'],
            [['name'], 'string', 'max' => 255],
        ];
    }
}
