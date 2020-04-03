<?php

use yii\db\Migration;

/**
 * Миграция для добавления внешних ключей в таблицы связанные с таблицей user
 *
 * Class m200319_072556_add_foreign_key_for_user_table
 */
class m200319_072556_add_foreign_key_for_user_table extends Migration
{
    /**
     * Добавляет внешние ключи в таблицы связанные с таблицей user
     *
     * @return bool|void
     */
    public function safeUp()
    {
        $this->addForeignKey('task-author-id', 'task', 'author_id', 'user',
            'id', 'CASCADE');
        $this->addForeignKey('task-respond-id', 'task_respond', 'user_id',
            'user', 'id', 'CASCADE');
        $this->addForeignKey('review-author-id', 'review', 'author_id', 'user',
            'id', 'CASCADE');
        $this->addForeignKey('review-executor-id', 'review', 'executor_id',
            'user', 'id', 'CASCADE');
        $this->addForeignKey('favorite-executor-client-id', 'favorite_executor',
            'client_id', 'user', 'id', 'CASCADE');
        $this->addForeignKey('favorite-executor-executor-id',
            'favorite_executor', 'executor_id', 'user', 'id', 'CASCADE');
        $this->addForeignKey('event-ribbon-user-id', 'event_ribbon', 'user_id',
            'user', 'id', 'CASCADE');
        $this->addForeignKey('user-data-user-id', 'user_data', 'user_id',
            'user', 'id', 'CASCADE');
        $this->addForeignKey('user-notifications-user-id', 'user_notifications',
            'user_id', 'user', 'id', 'CASCADE');
        $this->addForeignKey('user-photo-user-id', 'user_photo', 'user_id',
            'user', 'id', 'CASCADE');
        $this->addForeignKey('user-settings-user-id', 'user_settings',
            'user_id', 'user', 'id', 'CASCADE');
        $this->addForeignKey('user-specialization-user-id',
            'user_specialization', 'user_id', 'user', 'id', 'CASCADE');
    }

    /**
     * Удаляет внешние ключи из таблиц связанных с таблицей user
     *
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropForeignKey('task-author-id', 'task');
        $this->dropForeignKey('task-respond-id', 'task_respond');
        $this->dropForeignKey('review-author-id', 'review');
        $this->dropForeignKey('review-executor-id', 'review');
        $this->dropForeignKey('favorite-executor-client-id',
            'favorite_executor');
        $this->dropForeignKey('favorite-executor-executor-id',
            'favorite_executor');
        $this->dropForeignKey('event-ribbon-user-id', 'event_ribbon');
        $this->dropForeignKey('user-data-user-id', 'user_data');
        $this->dropForeignKey('user-notifications-user-id',
            'user_notifications');
        $this->dropForeignKey('user-photo-user-id', 'user_photo');
        $this->dropForeignKey('user-settings-user-id', 'user_settings');
        $this->dropForeignKey('user-specialization-user-id',
            'user_specialization');
    }
}
