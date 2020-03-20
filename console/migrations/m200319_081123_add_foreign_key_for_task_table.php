<?php

use yii\db\Migration;

/**
 * Миграция для добавления внешних ключей в таблицы связанные с таблицей task
 *
 * Class m200319_081123_add_foreign_key_for_task_table
 */
class m200319_081123_add_foreign_key_for_task_table extends Migration
{
    /**
     * Добавляет внешние ключи в таблицы связанные с таблицей task
     *
     * @return bool|void
     */
    public function safeUp()
    {
        $this->addForeignKey('event-ribbon-task-id','event_ribbon','task_id','task','id','CASCADE');
        $this->addForeignKey('message-task-id','message','task_id','task','id','CASCADE');
        $this->addForeignKey('review-task-id','review','task_id','task','id','CASCADE');
        $this->addForeignKey('task-file-task-id','task_file','task_id','task','id','CASCADE');
        $this->addForeignKey('task-respond-task-id','task_respond','task_id','task','id','CASCADE');
    }

    /**
     * Удаляет внешние ключи из таблиц связанных с таблицей task
     *
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropForeignKey('event-ribbon-task-id','event_ribbon');
        $this->dropForeignKey('message-task-id','message');
        $this->dropForeignKey('review-task-id','review');
        $this->dropForeignKey('task-file-task-id','task_file');
        $this->dropForeignKey('task-respond-task-id','task_respond');
    }
}
