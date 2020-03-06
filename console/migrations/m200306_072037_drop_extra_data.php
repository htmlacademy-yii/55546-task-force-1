<?php

use yii\db\Migration;

/**
 * Class m200306_072037_drop_extra_data
 */
class m200306_072037_drop_extra_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('chat');
        $this->dropColumn('user', 'auth_key');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('chat', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'task_id' => $this->integer()->notNull(),
        ]);
        $this->addColumn('user', 'auth_key', $this->char(255));
    }
}
