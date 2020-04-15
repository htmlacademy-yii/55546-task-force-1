<?php

namespace frontend\controllers;

use app\models\SettingsForm;
use app\models\Category;
use app\models\City;
use src\UserSettingsHelper\UserSettingsHelper;
use Yii;

/**
 * Контроллер для работы с настройками пользователя
 *
 * Class SettingsController
 *
 * @package frontend\controllers
 */
class SettingsController extends SecuredController
{
    /** @var string строка с адресом директории для хранения аватарок пользователей */
    public $avatarsPath = '';

    /** @var string строка с адресом директории для хранения фотографий работ исполнителей */
    public $photosPath = '';

    /**
     * Действие для страницы с определением основных настроек пользователя
     *
     * @return string шаблон с данными страницы
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     * @throws \yii\web\ServerErrorHttpException
     */
    public function actionIndex(): string
    {
        $user = Yii::$app->user->identity;
        $model = new SettingsForm();

        if (Yii::$app->request->isPost) {
            $model->setAvatar();
        }

        if (Yii::$app->request->isPost
            && $model->load(Yii::$app->request->post())
            && $model->validate()
        ) {
            (new UserSettingsHelper($model, $user))
                ->updateFileWorks("$this->photosPath/$user->id")
                ->updateUser()
                ->updateUserData($this->avatarsPath)
                ->updateUserNotifications()
                ->updateUserSettings()
                ->updateUserSpecializations()
                ->updateUserRole();
        }

        return $this->render('index', [
            'user' => $user,
            'model' => $model,
            'categories' => Category::getCategoriesArray(),
            'cities' => City::getCitiesArray(),
        ]);
    }
}
