<?php

namespace frontend\controllers;

use app\models\City;
use app\models\Task;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;

/**
 * Базовый контроллер для определения прав доступа к страницам сайта
 *
 * Class SecuredController
 *
 * @package frontend\controllers
 */
class SecuredController extends Controller
{
    /** @var \yii\db\ActiveRecord объект авторизованного пользователя */
    public $user;
    /** @var bool статус пользователя, авторизован он, или нет */
    public $isGuest;
    /** @var array массив со списком городов */
    public $cities;
    /** @var int выбранный город */
    public $selectedCity;
    /** @var array массив со списком событий */
    public $events;

    /**
     * Общие действия для всех контроллеров перед началом работы
     *
     * @param $action
     *
     * @return bool
     * @throws \yii\web\BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        $this->cities = City::getCitiesArray();
        $this->isGuest = Yii::$app->user->isGuest;
        Yii::$app->user->loginUrl = Url::to('/site/index');
        if ($this->user = Yii::$app->user->identity) {
            $this->user->updateLastActivity();
            $this->events = $this->user->events;
            $this->selectedCity = Yii::$app->session->get('city') ??
                $this->user->city_id;
        }

        Task::updateAll(['status' => Task::STATUS_EXPIRED],
            'date_end IS NOT NULL AND NOW() > date_end');

        return true;
    }

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
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }
}
