<?php

use yii\db\Migration;

/**
 * Миграция для добавления авторизации через VK
 *
 * Class m200302_054255_oauth
 */
class m200302_054255_oauth extends Migration
{
    /**
     * Добавляет новую таблицу для хранения данных пользоваетелй заходящих на сайт чере VK
     * и связанный с ней столбец в таблицу user
     *
     * @return bool|void
     */
    public function safeUp()
    {
        $this->addColumn('user', 'auth_key', \yii\db\Schema::TYPE_STRING);
        $this->createTable('auth', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'source' => $this->integer()->notNull(),
            'source_id' => $this->integer()->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    /**
     * Удаляет таблицу с данными пользоваетелй заходящих на сайт чере VK
     * и связанный с ней столбец в таблице user
     *
     * @return bool
     */
    public function safeDown()
    {
        $this->dropTable('auth');
        $this->dropColumn('user', 'auth_key');

        return true;
    }
}
