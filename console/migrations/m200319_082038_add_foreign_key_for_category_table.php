<?php

use yii\db\Migration;

/**
 * Миграция для добавления внешних ключей в таблицы связанные с таблицей category
 *
 * Class m200319_082038_add_foreign_key_for_category_table
 */
class m200319_082038_add_foreign_key_for_category_table extends Migration
{
    /**
     * Добавляет внешние ключи в таблицы связанные с таблицей category
     *
     * @return bool|void
     */
    public function safeUp()
    {
        $this->addForeignKey('task-category-id','task','category_id','category','id','CASCADE');
        $this->addForeignKey('user-specialization-category-id','user_specialization','category_id','category','id','CASCADE');
    }

    /**
     * Удаляет внешние ключи из таблиц связанных с таблицей category
     *
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropForeignKey('task-category-id','task');
        $this->dropForeignKey('user-specialization-category-id','user_specialization');
    }
}
