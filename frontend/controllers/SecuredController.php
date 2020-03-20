<?php
namespace frontend\controllers;

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
    /**
     * Общие действия для всех контроллеров перед началом работы
     *
     * @param $action
     *
     * @return bool
     * @throws \yii\db\Exception
     */
    public function beforeAction($action)
    {
        Yii::$app->user->loginUrl = Url::to('/site/index');
        $user = Yii::$app->user->identity;
        if($user) {
            Yii::$app->db->createCommand("UPDATE user SET last_activity = NOW() WHERE id = :id",
                [':id' => $user->id])->execute();
        }

        Task::updateAll(['status' => Task::STATUS_EXPIRED], "date_end IS NOT NULL AND NOW() > date_end");

        return $action;
    }

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
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }
}
