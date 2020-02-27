<?php
namespace frontend\controllers;

use app\models\AccountForm;
use app\models\Category;
use app\models\City;
use app\models\UserData;
use app\models\UserNotifications;
use app\models\UserSettings;
use app\models\UserSpecialization;
use frontend\components\DebugHelper\DebugHelper;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

class ProfileController extends SecuredController
{
    /** @var string  */
    public $avatarsPath = '';

    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        $model = new AccountForm();

        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            // обновление основных данных пользователя
            $user->login = $model->name;
            $user->email = $model->email;
            $user->city_id = $model->cityId;
            if(!empty($model->password)) {
                $user->password = Yii::$app->getSecurity()->generatePasswordHash($model->password);
            }
            $user->save();

            $userData = UserData::findOne(['user_id' => $user->id]);
            // обновление картинки
            $model->avatar = UploadedFile::getInstance($model, 'avatar');
            if($model->avatar) {
                $filePath = "{$this->avatarsPath}/{$model->avatar->baseName}.{$model->avatar->extension}";
                $model->avatar->saveAs($filePath);

                if($userData->avatar) {
                    unlink($userData->avatar);
                }

                $userData->avatar = $filePath;
            }

            // обновление вторичных данных пользователя
            $userData->birthday = $model->birthday;
            $userData->description = $model->description;
            $userData->phone = $model->phone;
            $userData->skype = $model->skype;
            $userData->other_messenger = $model->otherMessenger;
            $userData->save();

            // обновление уведомлений
            $userNotifications = UserNotifications::findOne(['user_id' => $user->id]);
            $userNotifications->is_new_message = (bool) $model->notifications['new-message'];
            $userNotifications->is_task_actions = (bool) $model->notifications['task-actions'];
            $userNotifications->is_new_review = (bool) $model->notifications['new-review'];
            $userNotifications->save();

            // обновление настроек
            $userSettings = UserSettings::findOne(['user_id' => $user->id]);
            $userSettings->is_hidden_contacts = (bool) $model->settings['show-only-client'];
            $userSettings->is_hidden_profile = (bool) $model->settings['hidden-profile'];
            $userSettings->save();

            // обновление категорий
            UserSpecialization::deleteAll(['user_id' => $user->id]);
            Yii::$app->db->createCommand()->batchInsert('user_specialization', ['user_id', 'category_id'], array_map(function($id) use ($user) {
                return [$user->id, $id];
            }, is_array($model->specializations) ? $model->specializations : []))->execute();
        }

        return $this->render('index', [
            'user' => $user,
            'model' => $model,
            'categories' => Category::find()->all(),
            'cities' => ArrayHelper::map(City::find()->select(['id', 'name'])->asArray()->all(), 'id', 'name'),
        ]);
    }
}
