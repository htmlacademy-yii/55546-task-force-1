<?php

namespace src\UserInitHelper;

use app\models\UserData;
use app\models\UserNotifications;
use app\models\UserSettings;
use common\models\User;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

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
    private $user;

    /**
     * UserInitHelper constructor.
     *
     * @param array $data массив с данными для создания нового пользователя
     */
    public function __construct(array $data)
    {
        $this->user = new User($data);
        $this->user->save();
    }

    /**
     * Возвращает созданый объект с новым пользователем
     *
     * @return User объект с новым пользователем
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param array $data массив с данными для дополнительной информации по пользователю
     *
     * @return UserInitHelper - текущий объект
     */
    public function initUserData(array $data = []): UserInitHelper
    {
        (new UserData(ArrayHelper::merge([
            'user_id' => $this->user->id,
            'description' => '',
            'other_messenger' => '',
            'avatar' => '',
            'views' => 0,
            'success_counter' => 0,
            'failing_counter' => 0,
        ], $data)))->save();

        return $this;
    }

    /**
     * @param array $data массив с данными настроек уведомлений пользователя
     *
     * @return UserInitHelper - текущий объект
     */
    public function initNotifications(array $data = []): UserInitHelper
    {
        (new UserNotifications(ArrayHelper::merge([
            'user_id' => $this->user->id,
            'is_new_message' => 0,
            'is_task_actions' => 0,
            'is_new_review' => 0,
        ], $data)))->save();

        return $this;
    }

    /**
     * @param array $data массив с данными настроек пользователя
     *
     * @return UserInitHelper - текущий объект
     */
    public function initSettings(array $data = []): UserInitHelper
    {
        (new UserSettings(ArrayHelper::merge([
            'user_id' => $this->user->id,
            'is_hidden_contacts' => 0,
            'is_hidden_profile' => 0,
        ], $data)))->save();

        return $this;
    }
}
