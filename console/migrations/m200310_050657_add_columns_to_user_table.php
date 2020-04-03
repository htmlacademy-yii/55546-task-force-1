<?php

use yii\db\Migration;

/**
 * Миграция для разделения счетчика выполненных заданий на две категории
 *
 * Class m200310_050657_add_columns_to_user_table
 */
class m200310_050657_add_columns_to_user_table extends Migration
{
    /**
     * Разделение счетчика выполненных заданий на две категории
     *
     * @return bool|void
     */
    public function safeUp()
    {
        $this->addColumn('user_data', 'success_counter',
            $this->integer()->defaultValue(0));
        $this->addColumn('user_data', 'failing_counter',
            $this->integer()->defaultValue(0));
        $this->dropColumn('user_data', 'order_count');
    }

    /**
     * Возврат единого счетчика выполенных заданий
     *
     * @return bool|void
     */
    public function safeDown()
    {
        $this->addColumn('user_data', 'order_count',
            $this->integer()->defaultValue(0));
        $this->dropColumn('user_data', 'success_counter');
        $this->dropColumn('user_data', 'failing_counter');
    }
}
