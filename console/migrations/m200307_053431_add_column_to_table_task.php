<?php

use yii\db\Migration;

/**
 * Миграция для добавления столбца с идентификатором города в таблицу заданий task
 *
 * Class m200307_053431_add_column_to_table_task
 */
class m200307_053431_add_column_to_table_task extends Migration
{
    /**
     * Добавления столбца с идентификатором города в таблицу заданий task
     *
     * @return bool|void
     */
    public function safeUp()
    {
        $this->addColumn('task', 'city_id', $this->integer());
    }

    /**
     * Удаление столбца с идентификатором города в таблице заданий task
     *
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropColumn('task', 'city_id');
    }
}
