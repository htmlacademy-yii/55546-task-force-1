<?php

use yii\db\Migration;

/**
 * Class m200307_085950_add_column_to_table_user
 */
class m200307_085950_add_column_to_table_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'last_activity', $this->timestamp());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'last_activity');
    }
}
