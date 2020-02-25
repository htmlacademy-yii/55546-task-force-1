<?php

use yii\db\Migration;

/**
 * Class m200221_043950_add_column_message_table
 */
class m200221_043950_add_column_message_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('message', 'task_id', $this->integer());
    }

    public function safeDown()
    {
        $this->dropColumn('message', 'task_id');
    }
}
