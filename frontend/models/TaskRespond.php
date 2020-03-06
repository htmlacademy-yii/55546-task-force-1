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

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
