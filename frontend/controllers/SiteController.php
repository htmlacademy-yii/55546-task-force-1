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
use yii\web\ErrorAction;
use yii\captcha\CaptchaAction;
use yii\authclient\AuthAction;

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
     */
    public function behaviors(): array
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
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
            'captcha' => [
                'class' => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => AuthAction::class,
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    /**
     * Действие для ajax установки в сессию активного города для фильтрации задач
     *
     * @param int $id идентификатор города
     */
    public function actionSetAjaxCity(int $id): void
    {
        Yii::$app->session->set('city', $id);
    }

    /**
     * Действие для ajax очистки просмотренных событий
     */
    public function actionClearEventRibbon(): void
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
    public function actionIndex(): string
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
     * @return array|null
     */
    public function actionLoginAjaxValidation(): ?array
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new LoginForm();
            $model->setAttributes(Yii::$app->request->post('LoginForm'));
            if ($validate = ActiveForm::validate($model)) {
                return $validate;
            }

            Yii::$app->user->login(User::findOne(['email' => $model->email]));

            $this->redirect(Task::getBaseTasksUrl());
        }

        return null;
    }

    /**
     * Действие для выхода пользователя из системы
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Действие для регистрации нового пользователя
     *
     * @return string шаблон с данными страницы
     * @throws \yii\base\Exception
     */
    public function actionSignup(): string
    {
        $model = new SignupForm();
        if (Yii::$app->request->isPost
            && $model->load(Yii::$app->request->post())
            && $model->validate()
            && User::create([
                'login' => $model->login,
                'email' => $model->email,
                'password' => Yii::$app->getSecurity()
                    ->generatePasswordHash($model->password),
                'city_id' => $model->cityId,
                'role' => User::ROLE_CLIENT,
            ])
        ) {
            $this->goHome();
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
    public function onAuthSuccess(VKontakte $client): Response
    {
        if ($user = Auth::onAuthVKontakte($client)) {
            Yii::$app->user->login($user);
        }

        return $this->redirect(Task::getBaseTasksUrl());
    }
}
