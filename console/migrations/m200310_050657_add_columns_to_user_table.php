<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 */
class m200310_050657_add_columns_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user_data', 'success_counter', $this->integer()->defaultValue(0));
        $this->addColumn('user_data', 'failing_counter', $this->integer()->defaultValue(0));
        $this->dropColumn('user_data', 'order_count');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('user_data', 'order_count', $this->integer()->defaultValue(0));
        $this->dropColumn('user_data', 'success_counter');
        $this->dropColumn('user_data', 'failing_counter');
    }
}
