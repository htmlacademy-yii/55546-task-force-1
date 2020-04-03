<?php

namespace src\UserInitHelper;

use yii\db\ActiveRecord;

/**
 * Класс для создания нового пользователя с необходимыми связанными записями
 *
 * Class UserInitHelper
 *
 * @package src\UserInitHelper
 */
class UserInitHelper
{
    /** @var ActiveRecord объект создаваемого пользователя */
    public $user;

    /**
     * UserInitHelper constructor.
     *
     * @param ActiveRecord $user - Объект для нового создаваемого пользователя
     *
     */
    public function __construct(ActiveRecord $user)
    {
        $this->user = $user;
        $this->user->save();
    }

    /**
     * @param ActiveRecord $uData - объект для связанной с пользователем записи данных пользователя
     *
     * @return UserInitHelper - текущий объект
     */
    public function initUserData(ActiveRecord $uData): UserInitHelper
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
            'rating' => '0',
            'views' => 0,
            'order_count' => 0,
        ];
        $uData->save();

        return $this;
    }

    /**
     * @param ActiveRecord $uNotifications - объект для связанной с пользователем записи уведомлений пользователя
     *
     * @return UserInitHelper - текущий объект
     */
    public function initNotifications(ActiveRecord $uNotifications
    ): UserInitHelper {
        $uNotifications->attributes = [
            'user_id' => $this->user->id,
            'is_new_message' => 0,
            'is_task_actions' => 0,
            'is_new_review' => 0,
        ];
        $uNotifications->save();

        return $this;
    }

    /**
     * @param ActiveRecord $uSettings - объект для связанной с пользователем записи настроек пользователя
     *
     * @return UserInitHelper - текущий объект
     */
    public function initSetting(ActiveRecord $uSettings): UserInitHelper
    {
        $uSettings->attributes = [
            'user_id' => $this->user->id,
            'is_hidden_contacts' => 0,
            'is_hidden_profile' => 0,
        ];
        $uSettings->save();

        return $this;
    }
}
