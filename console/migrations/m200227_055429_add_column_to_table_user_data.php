<?php

use yii\db\Migration;

/**
 * Class m200227_055429_add_column_to_table_user_data
 */
class m200227_055429_add_column_to_table_user_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user_data', 'birthday', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user_data', 'birthday');
    }
}
