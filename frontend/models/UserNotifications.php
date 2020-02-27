<?php
namespace app\models;

use yii\db\ActiveRecord;

class UserNotifications extends ActiveRecord
{
    public static function primaryKey()
    {
        return ['user_id'];
    }

    public function rules()
    {
        return [
            ['user_id', 'integer'],
            [['is_new_message', 'is_task_actions', 'is_new_review'], 'boolean'],
        ];
    }

    public static function tableName()
    {
        return 'user_notifications';
    }
}
