<?php

use yii\db\Migration;

/**
 * Миграция для добавления дополнительного столца с идентификатором в таблицу message
 *
 * Class m200221_043950_add_column_message_table
 */
class m200221_043950_add_column_message_table extends Migration
{
    /**
     * Добавляет идентификатор в таблицу message
     *
     * @return bool|void
     */
    public function safeUp()
    {
        $this->addColumn('message', 'task_id', $this->integer());
    }

    /**
     * Удаляет идентификатор из таблицы message
     *
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropColumn('message', 'task_id');
    }
}
