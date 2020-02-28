<?php

use yii\db\Migration;

/**
 * Class m200228_061905_create_table_vk_auth
 */
class m200228_061905_create_table_vk_auth extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_vk', [
            // поля для сервиса
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'source' => $this->string()->notNull(),
            'source_id' => $this->string()->notNull(),
            // поля для сайта
            'login' => $this->char()->notNull(),
            'email' => $this->char()->notNull(),
            'password' => $this->char()->notNull(),
            'city_id' => $this->integer(),
            'date_registration' => $this->timestamp(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user_vk');
    }
}
