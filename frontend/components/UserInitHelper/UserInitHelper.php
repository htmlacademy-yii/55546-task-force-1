<?php
namespace frontend\components\UserInitHelper;

use app\models\Auth;
use app\models\UserData;
use app\models\UserNotifications;
use app\models\UserSettings;
use common\models\User;
use Yii;

class UserInitHelper
{
    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->user->save();
    }

    public function initUserData(UserData $uData): UserInitHelper
    {
        $uData->attributes = [
            'user_id' => $this->user->id,
            'description' => '',
            'age' => '',
            'address' => '',
            'skype' => '',
            'phone' => '',
            'other_messenger' => '',
            'avatar' => $uData->avatar ?? '',
            'rating' => 0,
            'views' => 0,
            'order_count' => 0
        ];
        $uData->save();
        return $this;
    }

    public function initNotifications(UserNotifications $uNotifications): UserInitHelper
    {
        $uNotifications->attributes = [
            'user_id' => $this->user->id,
            'is_new_message' => 0,
            'is_task_actions' => 0,
            'is_new_review' => 0,
        ];
        $uNotifications->save();
        return $this;
    }

    public function initSetting(UserSettings $uSettings): UserInitHelper
    {
        $uSettings->attributes = [
            'user_id' => $this->user->id,
            'is_hidden_contacts' => 0,
            'is_hidden_profile' => 0
        ];
        $uSettings->save();
        return $this;
    }

    public static function deleteUser(int $userId)
    {
        User::findOne(['id' => $userId])->delete();
        UserData::findOne(['user_id' => $userId])->delete();
        UserNotifications::findOne(['user_id' => $userId])->delete();
        UserSettings::findOne(['user_id' => $userId])->delete();

        $auth = Auth::findOne(['user_id' => $userId]);
        if($auth) {
            $auth->delete();
        }
    }
}
