<?php

use yii\db\Migration;

/**
 * Миграция для добавления первичных ключей в те таблицы где их не было
 *
 * Class m200306_071647_update_to_criterion
 */
class m200306_071647_update_to_criterion extends Migration
{
    /**
     * Добавление первичных ключей в те таблицы где их не было
     *
     * @return bool|void
     */
    public function safeUp()
    {
        $this->addColumn('user_specialization', 'id', $this->primaryKey());
        $this->addColumn('user_settings', 'id', $this->primaryKey());
        $this->addColumn('user_photo', 'id', $this->primaryKey());
        $this->addColumn('user_notifications', 'id', $this->primaryKey());
        $this->addColumn('task_file', 'id', $this->primaryKey());
    }

    /**
     * Удаление добавленных первичных ключей из обновлённых таблиц
     *
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropColumn('user_specialization', 'id');
        $this->dropColumn('user_settings', 'id');
        $this->dropColumn('user_photo', 'id');
        $this->dropColumn('user_notifications', 'id');
        $this->dropColumn('task_file', 'id');
    }
}
