<?php

use yii\db\Migration;

/**
 * Миграция для добавления внешнего ключа в таблицу auth
 *
 * Class m200319_124036_add_foreign_key_for_user_table
 */
class m200319_124036_add_foreign_key_for_user_table extends Migration
{
    /**
     * Добавление внешнего ключа в таблицу auth
     *
     * @return bool|void
     */
    public function safeUp()
    {
        $this->addForeignKey('auth-user-id','auth','user_id','user','id','CASCADE');
    }

    /**
     * Удаление внешнего ключа из таблицы auth
     *
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropForeignKey('auth-user-id','auth');
    }
}
