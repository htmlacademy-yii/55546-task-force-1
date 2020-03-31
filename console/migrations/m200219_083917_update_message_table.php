<?php

use yii\db\Migration;

/**
 * Миграция для обновления таблицы сообщений message
 *
 * Class m200219_083917_update_message_table
 */
class m200219_083917_update_message_table extends Migration
{
    /**
     * Обновляет структуру таблицы message
     *
     * @return bool|void
     */
    public function safeUp()
    {
        $this->dropColumn('message', 'chat_id');
        $this->dropColumn('message', 'author_id');
        $this->dropColumn('message', 'public_date');
        $this->dropColumn('message', 'text');

        $this->addColumn('message', 'message', $this->text());
        $this->addColumn('message', 'published_at', $this->timestamp());
        $this->addColumn('message', 'is_mine', $this->boolean());
    }

    /**
     * Возвращает раннюю структуру таблицы message
     *
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropColumn('message', 'message');
        $this->dropColumn('message', 'published_at');
        $this->dropColumn('message', 'is_mine');

        $this->addColumn('message', 'chat_id', $this->integer());
        $this->addColumn('message', 'author_id', $this->integer());
        $this->addColumn('message', 'public_date', $this->timestamp());
        $this->addColumn('message', 'text', $this->text());
    }
}
