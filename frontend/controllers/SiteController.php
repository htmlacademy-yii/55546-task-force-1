<?php
namespace frontend\controllers;

use app\models\{Auth,
    City,
    SignupForm,
    Task,
    LoginForm,
    UserData,
    UserNotifications,
    UserSettings};
use common\models\User;
use frontend\components\UserInitHelper\UserInitHelper;
use Yii;
use yii\filters\VerbFilter;

use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Site controller
 */
class SiteController extends SecuredController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return ArrayHelper::merge([
            'access' => [
                'except' => ['index', 'signup', 'login', 'auth', 'login-ajax-validation'],
            ],
//            'verbs' => [
//                'class' => VerbFilter::class,
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
        ], parent::behaviors());
    }

    /**
     * {@inheritdoc}
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
            ]
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(Task::getBaseTasksUrl());
        }
        $this->layout = 'landing';

        return $this->render('landing', [
            'model' => new LoginForm(),
            'tasks' => Task::find()->with(['category'])->where(['status' => Task::STATUS_NEW])
                ->orderBy('date_start DESC')->limit(4)->all(),
        ]);
    }

    public function actionLoginAjaxValidation()
    {
        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $model = new LoginForm();
            $model->setAttributes(Yii::$app->request->post('LoginForm'));
            if($validate = ActiveForm::validate($model)) {
                return $validate;
            }

            Yii::$app->user->login(User::findOne(['email' => $model->email]));
            return $this->redirect(Task::getBaseTasksUrl());
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if(Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                (new UserInitHelper(new User([
                    'login' => $model->login,
                    'email' => $model->email,
                    'password' => Yii::$app->getSecurity()->generatePasswordHash($model->password),
                    'city_id' => $model->cityId,
                    'role' => User::ROLE_CLIENT,
                ])))->initNotifications(new UserNotifications())
                    ->initSetting(new UserSettings())
                    ->initUserData(new UserData());
                $transaction->commit();
                return $this->goHome();
            } catch (\Exception $e) {
                $transaction->rollBack();
            }
        }

        return $this->render('signup', [
            'model' => $model,
            'cities' => ArrayHelper::map(City::find()->asArray()->all(), 'id', 'name')
        ]);
    }

    public function onAuthSuccess($client)
    {
        // если пользователь зарегистрирован, то и новая регистрация ему не нужна
        if(!Yii::$app->user->isGuest) {
            return $this->redirect(Task::getBaseTasksUrl());
        }

        $clientId = $client->getId();
        $attributes = $client->getUserAttributes();

        $auth = Auth::findOne(['source' => $clientId, 'source_id' => $attributes['id']]);
        $user = null;
        if($auth) { // Пользователь гость, но уже имеет аккаунт через VK
            $user = $auth->user;
        } else { // Пользователь гость, и ещё не имеет аккаунта VK
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $user = (new UserInitHelper(new User([
                    'login' => $attributes['first_name'] . ' ' . $attributes['last_name'],
                    'email' => $attributes['email'],
                    'password' => Yii::$app->security->generateRandomString(6),
                    'city_id' => null,
                    'role' => User::ROLE_CLIENT,
                ])))->initNotifications(new UserNotifications())
                    ->initSetting(new UserSettings())
                    ->initUserData(new UserData(['avatar' => $attributes['photo']]))
                    ->user;
                (new Auth([
                    'user_id' => $user->id,
                    'source' => $clientId,
                    'source_id' => $attributes['id'],
                ]))->save();
                $transaction->commit();
            } catch (\Exception $err) {
                $transaction->rollBack();
            }
        }

        if($user) {
            Yii::$app->user->login($user);
        }

        return $this->redirect(Task::getBaseTasksUrl());
    }
}
