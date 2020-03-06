<?php

use yii\db\Migration;

/**
 * Class m200306_111907_create_table_favorite_executor
 */
class m200306_111907_create_table_favorite_executor extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('favorite_executor', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer()->notNull(),
            'executor_id' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('favorite_executor');
    }
}
