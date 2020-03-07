<?php

use yii\db\Migration;

/**
 * Class m200307_105042_drop_task_table_column_is_telework
 */
class m200307_105042_drop_task_table_column_is_telework extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('task', 'is_telework');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('task', 'is_telework', $this->boolean()->defaultValue(false));
    }
}
