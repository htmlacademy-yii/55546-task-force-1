<?php

use yii\db\Migration;

/**
 * Миграция для добавления столбца с временем последней активности пользователя
 *
 * Class m200307_085950_add_column_to_table_user
 */
class m200307_085950_add_column_to_table_user extends Migration
{
    /**
     * Добавление столбца с временем последней активности пользователя
     *
     * @return bool|void
     */
    public function safeUp()
    {
        $this->addColumn('user', 'last_activity', $this->timestamp());
    }

    /**
     * Удаление столбца с временем последней активности пользователя
     *
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'last_activity');
    }
}
