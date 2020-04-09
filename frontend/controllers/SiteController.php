<?php

namespace frontend\controllers;

use yii\authclient\clients\VKontakte;
use app\models\{Auth,
    City,
    EventRibbon,
    SignupForm,
    Task,
    LoginForm};
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Контроллер для работы с общими страницами сайта
 *
 * Class SiteController
 *
 * @package frontend\controllers
 */
class SiteController extends SecuredController
{
    /**
     * Определением фильтра
     *
     * @return array
     * @throws \yii\db\Exception
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['signup', 'login', 'auth', 'onAuthSuccess'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    /**
     * Действие для ajax установки в сессию активного города для фильтрации задач
     *
     * @param int $id идентификатор города
     */
    public function actionSetAjaxCity(int $id)
    {
        Yii::$app->session->set('city', $id);
    }

    /**
     * Действие для ajax очистки просмотренных событий
     */
    public function actionClearEventRibbon()
    {
        if ($user = Yii::$app->user->identity) {
            EventRibbon::deleteAll(['user_id' => $user->id]);
        }
    }

    /**
     * Действие для главной страницы стайта
     *
     * @return string шаблон с данными страницы
     */
    public function actionIndex()
    {
        $this->layout = 'landing';

        return $this->render('landing', [
            'model' => new LoginForm(),
            'tasks' => Task::find()->with(['category'])
                ->where(['status' => Task::STATUS_NEW])
                ->orderBy('date_start DESC')->limit(4)->all(),
        ]);
    }

    /**
     * Действие для ajax валидации формы авторизации
     *
     * @return array|Response
     */
    public function actionLoginAjaxValidation()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new LoginForm();
            $model->setAttributes(Yii::$app->request->post('LoginForm'));
            if ($validate = ActiveForm::validate($model)) {
                return $validate;
            }

            Yii::$app->user->login(User::findOne(['email' => $model->email]));

            return $this->redirect(Task::getBaseTasksUrl());
        }
    }

    /**
     * Действие для выхода пользователя из системы
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Действие для регистрации нового пользователя
     *
     * @return string|Response шаблон с данными страницы
     * @throws \yii\base\Exception
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if (Yii::$app->request->isPost
            && $model->load(Yii::$app->request->post())
            && $model->validate()
            && User::createUser([
                'login' => $model->login,
                'email' => $model->email,
                'password' => Yii::$app->getSecurity()
                    ->generatePasswordHash($model->password),
                'city_id' => $model->cityId,
            ])
        ) {
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
            'cities' => City::getCitiesArray(),
        ]);
    }

    /**
     * Действие для авторизации пользователя с помощью API VK
     *
     * @param VKontakte $client объект с данными пользователя из VK
     *
     * @return Response
     */
    public function onAuthSuccess(VKontakte $client)
    {
        if ($user = Auth::onAuthVKontakte($client)) {
            Yii::$app->user->login($user);
        }

        return $this->redirect(Task::getBaseTasksUrl());
    }
}
