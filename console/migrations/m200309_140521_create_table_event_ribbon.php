<?php

use yii\db\Migration;

/**
 * Class m200309_140521_create_table_event_ribbon
 */
class m200309_140521_create_table_event_ribbon extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('event_ribbon', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'task_id' => $this->integer(),
            'type' => $this->char(255),
            'message' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('event_ribbon');
    }
}
