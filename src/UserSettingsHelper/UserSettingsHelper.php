<?php

namespace src\UserSettingsHelper;

use app\models\SettingsForm;
use app\models\UserPhoto;
use app\models\UserSpecialization;
use common\models\User;
use Yii;
use yii\helpers\FileHelper;

/**
 * Класс для работы c разделённым обновлением настроек пользователя
 *
 * Class UserSettingsHelper
 *
 * @package src\UserSettingsHelper
 */
class UserSettingsHelper
{
    /**
     * Начальная инициализация класса помошника
     *
     * UserSettingsHelper constructor.
     *
     * @param SettingsForm $model объект модели формы с валиднами данными
     * @param User         $user  объект текущего пользователя
     *
     * @throws \yii\base\Exception
     */
    public function __construct(SettingsForm $model, User $user)
    {
        $this->model = $model;
        $this->user = $user;

        $this->updateUser();
        $this->updateUserNotifications();
        $this->updateUserSettings();
        $this->updateUserSpecializations();
        $this->updateUserRole();
    }

    /**
     * Обновление фотографий с примерами работ пользователя
     *
     * @param string $dir строка с адресом директории для сохранения фотографий
     *
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     * @throws \yii\web\ServerErrorHttpException
     */
    public function updateFileWorks(string $dir): void
    {
        if ($files = $this->model->getFiles()) {
            UserPhoto::deleteAll(['user_id' => $this->user->id]);
            if (FileHelper::findFiles($dir)) {
                FileHelper::removeDirectory($dir);
            }
            FileHelper::createDirectory($dir);
            (new UserPhoto(['path' => $dir]))->setPhotos($files);
        }
    }

    /**
     * Обновления второстепенной информации пользователя
     *
     * @param string $dir строка с адресом директории для сохранения аватарки пользователя
     *
     * @throws \yii\base\ErrorException
     */
    public function updateUserData(string $dir): void
    {
        $userData = $this->user->userData;
        if ($avatar = $this->model->getAvatar()) {
            $filePath
                = "{$dir}/{$avatar->baseName}.{$avatar->extension}";
            if ($userData->avatar && file_exists($userData->avatar)) {
                FileHelper::removeDirectory($userData->avatar);
            }

            $avatar->saveAs($filePath);
            $userData->avatar = $filePath;
        }

        $userData->birthday = $this->model->birthday;
        $userData->description = $this->model->description;
        $userData->phone = $this->model->phone;
        $userData->skype = $this->model->skype;
        $userData->other_messenger = $this->model->otherMessenger;
        $userData->save();
    }

    /**
     * Обновления основной информации пользователя
     *
     * @throws \yii\base\Exception
     */
    private function updateUser(): void
    {
        $this->user->login = $this->model->name;
        $this->user->email = $this->model->email;
        $this->user->city_id = $this->model->cityId;
        if (!empty($this->model->password)) {
            $this->user->password = Yii::$app->getSecurity()
                ->generatePasswordHash($this->model->password);
        }
        $this->user->save();
    }

    /**
     * Обновление активных уведомлений пользователя
     */
    private function updateUserNotifications(): void
    {
        $userNotifications = $this->user->userNotifications;
        $userNotifications->is_new_message
            = (bool)$this->model->notifications['new-message'];
        $userNotifications->is_task_actions
            = (bool)$this->model->notifications['task-actions'];
        $userNotifications->is_new_review
            = (bool)$this->model->notifications['new-review'];
        $userNotifications->save();
    }

    /**
     * Обновление настроек пользователя
     */
    private function updateUserSettings(): void
    {
        $userSettings = $this->user->userSettings;
        $userSettings->is_hidden_contacts
            = (bool)$this->model->settings['show-only-client'];
        $userSettings->is_hidden_profile
            = (bool)$this->model->settings['hidden-profile'];
        $userSettings->save();
    }

    /**
     * Обновление специализаций пользователя
     *
     * @throws \yii\db\Exception
     */
    private function updateUserSpecializations(): void
    {
        $specializations = is_array($this->model->specializations)
            ? $this->model->specializations : [];
        UserSpecialization::deleteAll(['user_id' => $this->user->id]);
        Yii::$app->db->createCommand()
            ->batchInsert('user_specialization', ['user_id', 'category_id'],
                array_map(function ($id) {
                    return [$this->user->id, $id];
                }, $specializations))->execute();
    }

    /**
     * Обновление роли пользователя
     */
    private function updateUserRole(): void
    {
        $specializations = is_array($this->model->specializations)
            ? $this->model->specializations : [];
        $this->user->role = empty($specializations) ? User::ROLE_CLIENT
            : User::ROLE_EXECUTOR;
        $this->user->save();
    }
}
