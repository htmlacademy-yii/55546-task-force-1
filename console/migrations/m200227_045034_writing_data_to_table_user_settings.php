<?php

use yii\db\Migration;
use yii\db\QueryBuilder;
use common\models\User;

/**
 * Class m200227_045034_writing_data_to_table_user_settings
 */
class m200227_045034_writing_data_to_table_user_settings extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->db->createCommand()->batchInsert('user_settings', ['user_id', 'is_hidden_contacts', 'is_hidden_profile'], array_map(function($id) {
            return [$id, 0, 0];
        }, User::find()->select('id')->asArray()->column()))->execute();
    }

    public function safeDown()
    {
        $this->db->createCommand()->delete('user_settings')->execute();
    }
}
