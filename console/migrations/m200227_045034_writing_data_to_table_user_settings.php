<?php

use yii\db\Migration;
use common\models\User;

/**
 * Миграция для инициализации таблицы user_settings данными для всех зарегистрированных пользователей
 *
 * Class m200227_045034_writing_data_to_table_user_settings
 */
class m200227_045034_writing_data_to_table_user_settings extends Migration
{
    /**
     * Инициализация таблицы user_settings данными для всех зарегистрированных пользователей
     *
     * @return bool|void
     * @throws \yii\db\Exception
     */
    public function safeUp()
    {
        $this->db->createCommand()->batchInsert('user_settings', ['user_id', 'is_hidden_contacts', 'is_hidden_profile'], array_map(function($id) {
            return [$id, 0, 0];
        }, User::find()->select('id')->asArray()->column()))->execute();
    }

    /**
     * Удаление таблицы user_settings
     *
     * @return bool|void
     * @throws \yii\db\Exception
     */
    public function safeDown()
    {
        $this->db->createCommand()->delete('user_settings')->execute();
    }
}
