<?php

use yii\db\Migration;

/**
 * Class m200302_112719_update_user_tables
 */
class m200302_112719_update_user_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('user_photo', 'user_id', $this->integer()->notNull());
        $this->alterColumn('user_data', 'user_id', $this->integer()->notNull());
        $this->alterColumn('user_settings', 'user_id', $this->integer()->notNull());
        $this->alterColumn('user_notifications', 'user_id', $this->integer()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('user_photo', 'user_id', $this->integer());
        $this->alterColumn('user_data', 'user_id', $this->integer());
        $this->alterColumn('user_settings', 'user_id', $this->integer());
        $this->alterColumn('user_notifications', 'user_id', $this->integer());
    }
}
