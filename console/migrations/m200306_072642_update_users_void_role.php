<?php

use yii\db\Migration;

/**
 * Миграция для обновления роли тех пользователей у которых она была не указана
 *
 * Class m200306_072642_update_users_void_role
 */
class m200306_072642_update_users_void_role extends Migration
{
    /**
     * Обновление роли тех пользователей у которых она была не указана
     *
     * @return bool|void
     */
    public function safeUp()
    {
        $this->update('user', ['role' => \common\models\User::ROLE_CLIENT], 'role IS NULL');
    }

    /**
     * @return bool
     */
    public function safeDown()
    {
        echo "m200306_072642_update_users_void_role cannot be reverted.\n";
        return false;
    }
}
