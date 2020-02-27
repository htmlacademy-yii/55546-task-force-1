<?php

use yii\db\Migration;
use common\models\User;

/**
 * Class m200226_151439_create_table_user_notifications
 */
class m200226_151439_create_table_user_notifications extends Migration
{

    public $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_notifications', [
            'user_id' => $this->integer(),
            'is_new_message' => $this->boolean(),
            'is_task_actions' => $this->boolean(),
            'is_new_review' => $this->boolean()
        ]);

        $this->db->createCommand()->batchInsert('user_notifications',
            ['user_id', 'is_new_message', 'is_task_actions', 'is_new_review'], array_map(function($id) {
                return [$id, 0, 0, 0];
            }, User::find()->select('id')->asArray()->column()))->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user_notifications');
    }
}
