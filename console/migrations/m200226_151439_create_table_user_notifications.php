<?php

use yii\db\Migration;
use common\models\User;

/**
 * Миграция для добалвления таблицы уведомлений пользователя user_notifications
 *
 * Class m200226_151439_create_table_user_notifications
 */
class m200226_151439_create_table_user_notifications extends Migration
{
    /**
     * Создаёт таблицу user_notifications и инициализирует её данными для всех зарегистрированных пользователей
     *
     * @return bool|void
     * @throws \yii\db\Exception
     */
    public function safeUp()
    {
        $this->createTable('user_notifications', [
            'user_id' => $this->integer(),
            'is_new_message' => $this->boolean(),
            'is_task_actions' => $this->boolean(),
            'is_new_review' => $this->boolean(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->db->createCommand()->batchInsert('user_notifications',
            ['user_id', 'is_new_message', 'is_task_actions', 'is_new_review'],
            array_map(function ($id) {
                return [$id, 0, 0, 0];
            }, User::find()->select('id')->asArray()->column()))->execute();
    }

    /**
     * Удаляет таблицу user_notifications
     *
     * @return bool|void
     */
    public function safeDown()
    {
        $this->dropTable('user_notifications');
    }
}
