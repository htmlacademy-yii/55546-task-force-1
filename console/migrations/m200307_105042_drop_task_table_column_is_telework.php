<?php

use yii\db\Migration;

/**
 * Миграция для удаления лишнего столбца из таблицы заданий task
 *
 * Class m200307_105042_drop_task_table_column_is_telework
 */
class m200307_105042_drop_task_table_column_is_telework extends Migration
{
    /**
     * Удаление лишнего столбца из таблицы заданий task
     *
     * @return bool|void
     */
    public function safeUp()
    {
        $this->dropColumn('task', 'is_telework');
    }

    /**
     * Возврат удалённого столбца в таблицу заданий task
     *
     * @return bool|void
     */
    public function safeDown()
    {
        $this->addColumn('task', 'is_telework', $this->boolean()->defaultValue(false));
    }
}
