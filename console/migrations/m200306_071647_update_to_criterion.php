<?php

use yii\db\Migration;

/**
 * Class m200306_071647_update_to_criterion
 */
class m200306_071647_update_to_criterion extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user_specialization', 'id', $this->primaryKey());
        $this->addColumn('user_settings', 'id', $this->primaryKey());
        $this->addColumn('user_photo', 'id', $this->primaryKey());
        $this->addColumn('user_notifications', 'id', $this->primaryKey());
        $this->addColumn('task_file', 'id', $this->primaryKey());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user_specialization', 'id');
        $this->dropColumn('user_settings', 'id');
        $this->dropColumn('user_photo', 'id');
        $this->dropColumn('user_notifications', 'id');
        $this->dropColumn('task_file', 'id');
    }
}
