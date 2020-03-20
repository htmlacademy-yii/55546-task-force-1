<?php
namespace frontend\src\UserSettingsHelper;

use app\models\UserPhoto;
use app\models\UserSpecialization;
use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\IdentityInterface;
use yii\web\UploadedFile;

/**
 * Класс для работы c разделённым обновлением настроек пользователя
 *
 * Class UserSettingsHelper
 *
 * @package frontend\src\UserSettingsHelper
 */
class UserSettingsHelper
{
    /**
     * Начальная инициализация класса помошника
     *
     * UserSettingsHelper constructor.
     *
     * @param Model             $model объект модели формы с валиднами данными
     * @param IdentityInterface $user объект текущего пользователя
     */
    public function __construct(Model $model, IdentityInterface $user)
    {
        $this->model = $model;
        $this->user = $user;
    }

    /**
     * Обновление фотографий с примерами работ пользователя
     *
     * @param string $dir строка с адресом директории для сохранения фотографий
     *
     * @return UserSettingsHelper текущий экземпляр класса помошника
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     * @throws \yii\web\ServerErrorHttpException
     * @throws \yii\web\UnsupportedMediaTypeHttpException
     */
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

    /**
     * Обновления основной информации пользователя
     *
     * @return UserSettingsHelper текущий экземпляр класса помошника
     * @throws \yii\base\Exception
     */
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

    /**
     * Обновления второстепенной информации пользователя
     *
     * @param string $dir строка с адресом директории для сохранения аватарки пользователя
     *
     * @return UserSettingsHelper текущий экземпляр класса помошника
     */
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

    /**
     * Обновление активных уведомлений пользователя
     *
     * @return UserSettingsHelper текущий экземпляр класса помошника
     */
    public function updateUserNotifications(): UserSettingsHelper
    {
        $userNotifications = $this->user->userNotifications;
        $userNotifications->is_new_message = (bool) $this->model->notifications['new-message'];
        $userNotifications->is_task_actions = (bool) $this->model->notifications['task-actions'];
        $userNotifications->is_new_review = (bool) $this->model->notifications['new-review'];
        $userNotifications->save();

        return $this;
    }

    /**
     * Обновление настроек пользователя
     *
     * @return UserSettingsHelper текущий экземпляр класса помошника
     */
    public function updateUserSettings(): UserSettingsHelper
    {
        $userSettings = $this->user->userSettings;
        $userSettings->is_hidden_contacts = (bool) $this->model->settings['show-only-client'];
        $userSettings->is_hidden_profile = (bool) $this->model->settings['hidden-profile'];
        $userSettings->save();

        return $this;
    }

    /**
     * Обновление специализаций пользователя
     *
     * @return UserSettingsHelper текущий экземпляр класса помошника
     * @throws \yii\db\Exception
     */
    public function updateUserSpecializations(): UserSettingsHelper
    {
        $specializations = is_array($this->model->specializations) ? $this->model->specializations : [];
        UserSpecialization::deleteAll(['user_id' => $this->user->id]);
        Yii::$app->db->createCommand()->batchInsert('user_specialization', ['user_id', 'category_id'], array_map(function($id) {
            return [$this->user->id, $id];
        }, $specializations))->execute();

        return $this;
    }

    /**
     * Обновление роли пользователя
     *
     * @param string $roleClient строка с ролько клиент
     * @param string $roleExecutor строка с ролько исполнителя
     *
     * @return UserSettingsHelper текущий экземпляр класса помошника
     */
    public function updateUserRole(string $roleClient, string $roleExecutor): UserSettingsHelper
    {
        $specializations = is_array($this->model->specializations) ? $this->model->specializations : [];
        $this->user->role = empty($specializations) ? $roleClient : $roleExecutor;
        $this->user->save();

        return $this;
    }
}
