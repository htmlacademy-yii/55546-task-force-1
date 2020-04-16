<?php

use yii\db\Migration;

/**
 * Миграция для установки дефолтного времени при создании нового задания
 *
 * Class m200416_040113_set_default_value_task_date_start
 */
class m200416_040113_set_default_value_task_date_start extends Migration
{
    /**
     * Установка дефолтного времени для поля date_start
     *
     * @return bool|void
     */
    public function safeUp()
    {
        $this->alterColumn('task', 'date_start',
            $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
    }

    /**
     * Возврат старого дефолтного времени для поля date_start
     *
     * @return bool|void
     */
    public function safeDown()
    {
        $this->alterColumn('task', 'date_start',
            $this->timestamp()->defaultValue(null));
    }
}
