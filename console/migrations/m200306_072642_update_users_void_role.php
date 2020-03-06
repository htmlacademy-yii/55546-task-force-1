<?php

use yii\db\Migration;

/**
 * Class m200306_072642_update_users_void_role
 */
class m200306_072642_update_users_void_role extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->update('user', ['role' => \common\models\User::ROLE_CLIENT], 'role IS NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200306_072642_update_users_void_role cannot be reverted.\n";
        return false;
    }
}
