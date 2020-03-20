<?php

use yii\db\Migration;

/**
 * Миграция для добавления в таблицу данных пользователя столбца с датой рождения
 *
 * Class m200227_055429_add_column_to_table_user_data
 */
class m200227_055429_add_column_to_table_user_data extends Migration
{
    /**
     * Добавляет в таблицу данных пользователя столбец с датой рождения
     *
     * @return bool|void
     */
    public function safeUp()
    {
        $this->addColumn('user_data', 'birthday', $this->date());
    }

    /**
     * Удаляет из таблицы данных пользователя столбец с датой рождения
     *
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropColumn('user_data', 'birthday');
    }
}
