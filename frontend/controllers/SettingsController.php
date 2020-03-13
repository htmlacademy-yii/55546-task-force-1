<?php
namespace frontend\controllers;

use app\models\SettingsForm;
use app\models\Category;
use app\models\City;
use app\models\UserNotifications;
use app\models\UserPhoto;
use app\models\UserSettings;
use app\models\UserSpecialization;
use common\models\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\validators\FileValidator;
use yii\web\NotAcceptableHttpException;
use yii\web\UnsupportedMediaTypeHttpException;
use yii\web\UploadedFile;

class SettingsController extends SecuredController
{
    /** @var string  */
    public $avatarsPath = '';

    /** @var string  */
    public $photosPath = '';

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            if(Yii::$app->request->isAjax) {
                $action->actionMethod = 'actionAjax';
            }
            return true;
        }

        return false;
    }

    public function actionAjax()
    {
        if (Yii::$app->request->isPost) {
            $image = UploadedFile::getInstanceByName('file[0]');
            if(!(new FileValidator(['skipOnEmpty' => false, 'extensions' => 'png, jpg']))->validate($image)) {
                throw new UnsupportedMediaTypeHttpException('Не допустимый формат файла');
            }

            $userPhoto = new UserPhoto(['path' => $this->photosPath]);
            $userPhoto->setPhoto($image);
            $userPhoto->save();
            $userPhoto->link('user', Yii::$app->user->identity);

            return $this->asJson('success');
        }
        throw new NotAcceptableHttpException();
    }

    public function actionIndex()
    {
        $user = Yii::$app->user->identity;
        $model = new SettingsForm();
        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            // обновление основных данных пользователя
            $user->login = $model->name;
            $user->email = $model->email;
            $user->city_id = $model->cityId;
            if(!empty($model->password)) {
                $user->password = Yii::$app->getSecurity()->generatePasswordHash($model->password);
            }
            $user->save();

            $userData = $user->userData;
            // обновление картинки
            $model->avatar = UploadedFile::getInstance($model, 'avatar');
            if($model->avatar) {
                $filePath = "{$this->avatarsPath}/{$model->avatar->baseName}.{$model->avatar->extension}";
                if($userData->avatar && file_exists($userData->avatar)) {
                    unlink($userData->avatar);
                }

                $model->avatar->saveAs($filePath);
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
            $specializations = is_array($model->specializations) ? $model->specializations : [];

            UserSpecialization::deleteAll(['user_id' => $user->id]);
            Yii::$app->db->createCommand()->batchInsert('user_specialization', ['user_id', 'category_id'], array_map(function($id) use ($user) {
                return [$user->id, $id];
            }, $specializations))->execute();

            // обновление роли пользователя
            $user->role = empty($specializations) ? User::ROLE_CLIENT : User::ROLE_EXECUTOR;
            $user->save();
        }

        return $this->render('index', [
            'user' => $user,
            'model' => $model,
            'categories' => Category::find()->all(),
            'cities' => ArrayHelper::map(City::find()->select(['id', 'name'])->asArray()->all(), 'id', 'name'),
        ]);
    }
}
