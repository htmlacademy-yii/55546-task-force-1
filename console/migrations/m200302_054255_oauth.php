<?php

use yii\db\Migration;

/**
 * Class m200302_054255_oauth
 */
class m200302_054255_oauth extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'auth_key', \yii\db\Schema::TYPE_STRING);
        $this->createTable('auth', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'source' => $this->integer()->notNull(),
            'source_id' => $this->integer()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('auth');
        $this->dropColumn('user', 'auth_key');

        return true;
    }
}
