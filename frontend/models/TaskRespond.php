<?php

namespace app\models;

use frontend\components\DebugHelper;
use Yii;
use yii\db\ActiveRecord;
use common\models\User;
/**
 * This is the model class for table "task_respond".
 *
 * @property int|null $task_id
 * @property int|null $user_id
 * @property string|null $text
 * @property string|null $public_date
 */
class TaskRespond extends ActiveRecord
{
    const STATUS_NEW = 'new';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DENIED = 'denied';

    public static function tableName()
    {
        return 'task_respond';
    }

    public function rules()
    {
        return [
            [['task_id', 'user_id', 'price'], 'integer'],
            [['text'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'task_id' => 'Task ID',
            'user_id' => 'User ID',
            'text' => 'Text',
            'public_date' => 'Public Date',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
