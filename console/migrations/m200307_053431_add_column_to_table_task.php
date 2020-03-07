<?php

use yii\db\Migration;

/**
 * Class m200307_053431_add_column_to_table_task
 */
class m200307_053431_add_column_to_table_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('task', 'city_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('task', 'city_id');
    }
}
