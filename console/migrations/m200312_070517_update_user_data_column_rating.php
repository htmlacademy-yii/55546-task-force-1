<?php

use yii\db\Migration;

/**
 * Миграция для обновления типа столбца с рейтингом пользователя
 *
 * Class m200312_070517_update_user_data_column_rating
 */
class m200312_070517_update_user_data_column_rating extends Migration
{
    /**
     * Обновление типа столбца с рейтингом пользователя
     *
     * @return bool|void
     */
    public function safeUp()
    {
        $this->alterColumn('user_data', 'rating', $this->char('255'));
    }

    /**
     * Возврат старого типа столбца с рейтингом пользователя
     *
     * @return bool|void
     */
    public function safeDown()
    {
        $this->alterColumn('user_data', 'rating', $this->integer());
    }
}
