<?php
namespace frontend\src\UserSettingsHelper;

use app\models\UserPhoto;
use app\models\UserSpecialization;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\IdentityInterface;
use yii\web\UploadedFile;

class UserSettingsHelper
{
    public function __construct(Model $model, IdentityInterface $user)
    {
        $this->model = $model;
        $this->user = $user;
    }

    public function updateFileWorks(string $dir): UserSettingsHelper
    {
        if($this->model->files) {
            UserPhoto::deleteAll(['user_id' => $this->user->id]);
            if(file_exists($dir)) {
                FileHelper::removeDirectory($dir);
            }
            FileHelper::createDirectory($dir);
            (new UserPhoto(['path' => $dir]))->setPhotos($this->model->files);
        }

        return $this;
    }

    public function updateUser(): UserSettingsHelper
    {
        $this->user->login = $this->model->name;
        $this->user->email = $this->model->email;
        $this->user->city_id = $this->model->cityId;
        if(!empty($this->model->password)) {
            $this->user->password = Yii::$app->getSecurity()->generatePasswordHash($this->model->password);
        }
        $this->user->save();

        return $this;
    }

    public function updateUserData(string $dir): UserSettingsHelper
    {
        $userData = $this->user->userData;
        $this->model->avatar = UploadedFile::getInstance($this->model, 'avatar');
        if($this->model->avatar) {
            $filePath = "{$dir}/{$this->model->avatar->baseName}.{$this->model->avatar->extension}";
            if($userData->avatar && file_exists($userData->avatar)) {
                unlink($userData->avatar);
            }

            $this->model->avatar->saveAs($filePath);
            $userData->avatar = $filePath;
        }

        $userData->birthday = $this->model->birthday;
        $userData->description = $this->model->description;
        $userData->phone = $this->model->phone;
        $userData->skype = $this->model->skype;
        $userData->other_messenger = $this->model->otherMessenger;
        $userData->save();

        return $this;
    }

    public function updateUserNotifications(): UserSettingsHelper
    {
        $userNotifications = $this->user->userNotifications;
        $userNotifications->is_new_message = (bool) $this->model->notifications['new-message'];
        $userNotifications->is_task_actions = (bool) $this->model->notifications['task-actions'];
        $userNotifications->is_new_review = (bool) $this->model->notifications['new-review'];
        $userNotifications->save();

        return $this;
    }

    public function updateUserSettings(): UserSettingsHelper
    {
        $userSettings = $this->user->userSettings;
        $userSettings->is_hidden_contacts = (bool) $this->model->settings['show-only-client'];
        $userSettings->is_hidden_profile = (bool) $this->model->settings['hidden-profile'];
        $userSettings->save();

        return $this;
    }

    public function updateUserSpecializations(): UserSettingsHelper
    {
        $specializations = is_array($this->model->specializations) ? $this->model->specializations : [];
        UserSpecialization::deleteAll(['user_id' => $this->user->id]);
        Yii::$app->db->createCommand()->batchInsert('user_specialization', ['user_id', 'category_id'], array_map(function($id) {
            return [$this->user->id, $id];
        }, $specializations))->execute();

        return $this;
    }

    public function updateUserRole(string $roleClient, string $roleExecutor): UserSettingsHelper
    {
        $specializations = is_array($this->model->specializations) ? $this->model->specializations : [];
        $this->user->role = empty($specializations) ? $roleClient : $roleExecutor;
        $this->user->save();

        return $this;
    }
}
