<?php

use yii\db\Migration;

/**
 * Class m200306_061959_transfer_column_user_data_to_user
 */
class m200306_061959_transfer_column_user_data_to_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('user_data', 'status');
        $this->addColumn('user', 'role', $this->char(255));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'role');
        $this->addColumn('user_data', 'status', $this->char(255));
    }
}
