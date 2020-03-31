<?php

use yii\db\Migration;

/**
 * Миграция для удаления лишних столбцов из таблицы с данными пользователя user_data
 *
 * Class m200306_111455_drop_extra_data_to_table_user_data
 */
class m200306_111455_drop_extra_data_to_table_user_data extends Migration
{
    /**
     * Удаления лишних столбцов из таблицы с данными пользователя user_data
     *
     * @return bool|void
     */
    public function safeUp()
    {
        $this->dropColumn('user_data', 'age');
        $this->dropColumn('user_data', 'address');
    }

    /**
     * Возврат удалённых столбцов из таблицы с данными пользователя user_data
     *
     * @return bool|void
     */
    public function safeDown()
    {
        $this->addColumn('user_data', 'age', $this->integer());
        $this->addColumn('user_data', 'address', $this->text());
    }
}
