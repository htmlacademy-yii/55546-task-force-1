<?php

use yii\db\Migration;

/**
 * Миграция для обновления типа столбца с типом ресурса в таблице auth
 *
 * Class m200422_031349_update_auth_column_source
 */
class m200422_031349_update_auth_column_source extends Migration
{
    /**
     * Обновление типа столбца с типом ресурса
     *
     * @return bool|void
     */
    public function safeUp()
    {
        $this->alterColumn('auth', 'source', $this->char('255'));
    }

    /**
     * Возврат старого типа столбца с типом ресурса
     *
     * @return void
     */
    public function safeDown()
    {
        $this->alterColumn('auth', 'source', $this->integer());
    }
}
