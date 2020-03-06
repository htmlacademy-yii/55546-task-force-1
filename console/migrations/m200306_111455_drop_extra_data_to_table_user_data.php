<?php

use yii\db\Migration;

/**
 * Class m200306_111455_drop_extra_data_to_table_user_data
 */
class m200306_111455_drop_extra_data_to_table_user_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('user_data', 'age');
        $this->dropColumn('user_data', 'address');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('user_data', 'age', $this->integer());
        $this->addColumn('user_data', 'address', $this->text());
    }
}
