<?php
namespace app\models;

use yii\db\ActiveRecord;

/**
 * Класс для работы с моделью настроек пользователя
 *
 * Class UserSettings
 *
 * @package app\models
 */
class UserSettings extends ActiveRecord
{
    /**
     * Returns the primary key name(s) for this AR class.
     * The default implementation will return the primary key(s) as declared
     * in the DB table that is associated with this AR class.
     *
     * If the DB table does not declare any primary key, you should override
     * this method to return the attributes that you want to use as primary keys
     * for this AR class.
     *
     * Note that an array should be returned even for a table with single primary key.
     *
     * @return string[] the primary keys of the associated database table.
     */
    public static function primaryKey(): array
    {
        return ['user_id'];
    }

    /**
     * Получение имени таблицы модели
     *
     * @return string имя таблицы модели
     */
    public static function tableName(): string
    {
        return 'user_settings';
    }

    /**
     * Получение списка правил валидации для модели
     *
     * @return array список правил валидации для модели
     */
    public function rules(): array
    {
        return [
            ['user_id', 'integer'],
            [['is_hidden_contacts', 'is_hidden_profile'], 'boolean'],
        ];
    }
}
