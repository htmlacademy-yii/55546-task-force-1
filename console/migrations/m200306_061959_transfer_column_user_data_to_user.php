<?php

use yii\db\Migration;

/**
 * Миграция для переноса столбца с ролью пользователя из таблицы user_data в таблицу user
 *
 * Class m200306_061959_transfer_column_user_data_to_user
 */
class m200306_061959_transfer_column_user_data_to_user extends Migration
{
    /**
     * Перенос столбца с ролью пользователя из таблицы user_data в таблицу user
     *
     * @return bool|void
     */
    public function safeUp()
    {
        $this->dropColumn('user_data', 'status');
        $this->addColumn('user', 'role', $this->char(255));
    }

    /**
     * Возврат столбца с ролью пользователя из таблицы user в таблицу user_data
     *
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'role');
        $this->addColumn('user_data', 'status', $this->char(255));
    }
}
