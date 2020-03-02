<?php
namespace app\models;

use common\models\User;
use yii\db\ActiveRecord;

class Auth extends ActiveRecord
{
    public static function tableName()
    {
        return 'auth';
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
