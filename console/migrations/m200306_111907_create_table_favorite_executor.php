<?php

use yii\db\Migration;

/**
 * Миграция для добавления таблицы избранных исполнителей favorite_executor
 *
 * Class m200306_111907_create_table_favorite_executor
 */
class m200306_111907_create_table_favorite_executor extends Migration
{
    /**
     * Добавление таблицы избранных исполнителей favorite_executor
     *
     * @return bool|void
     */
    public function safeUp()
    {
        $this->createTable('favorite_executor', [
            'id' => $this->primaryKey(),
            'client_id' => $this->integer()->notNull(),
            'executor_id' => $this->integer()->notNull(),
        ]);
    }

    /**
     * Удаление таблицы избранных исполнителей favorite_executor
     *
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable('favorite_executor');
    }
}
