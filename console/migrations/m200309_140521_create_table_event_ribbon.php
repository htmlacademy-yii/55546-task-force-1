<?php

use yii\db\Migration;

/**
 * Миграция для добавления таблицы ленты событий пользователей event_ribbon
 *
 * Class m200309_140521_create_table_event_ribbon
 */
class m200309_140521_create_table_event_ribbon extends Migration
{
    /**
     * Добавление таблицы ленты событий пользователей
     *
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable('event_ribbon', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'task_id' => $this->integer(),
            'type' => $this->char(255),
            'message' => $this->text(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');
    }

    /**
     * Удаление таблицы ленты событий пользователей
     *
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable('event_ribbon');
    }
}
