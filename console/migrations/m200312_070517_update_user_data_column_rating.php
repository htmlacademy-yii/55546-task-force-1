<?php

use yii\db\Migration;

/**
 * Class m200312_070517_update_user_data_column_rating
 */
class m200312_070517_update_user_data_column_rating extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('user_data', 'rating', $this->char('255'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('user_data', 'rating', $this->integer());
    }
}
