<?php

use yii\db\Migration;

/**
 * Миграция для удаления лишних полей и таблиц из базы данных
 *
 * Class m200306_072037_drop_extra_data
 */
class m200306_072037_drop_extra_data extends Migration
{
    /**
     * Удаления лишних полей и таблиц из базы данных
     *
     * @return bool|void
     */
    public function safeUp()
    {
        $this->dropTable('chat');
        $this->dropColumn('user', 'auth_key');
    }

    /**
     * Возврат удалённых полей и таблиц
     *
     * @return bool|void
     */
    public function safeDown()
    {
        $this->createTable('chat', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'task_id' => $this->integer()->notNull(),
        ]);
        $this->addColumn('user', 'auth_key', $this->char(255));
    }
}
