<?php

use yii\db\Migration;

/**
 * Миграция для удаления лишнего столбца из таблицы user_data
 *
 * Class m200409_094938_drop_user_data_table_column_rating
 */
class m200409_094938_drop_user_data_table_column_rating extends Migration
{
    /**
     * Удаление лишнего столбца из таблицы user_data
     *
     * @return bool|void
     */
    public function safeUp()
    {
        $this->dropColumn('user_data', 'rating');
    }

    /**
     * Возврат удалённого столбца в таблицу user_data
     *
     * @return bool|void
     */
    public function safeDown()
    {
        $this->addColumn('user_data', 'rating', $this->char('255'));
    }
}
