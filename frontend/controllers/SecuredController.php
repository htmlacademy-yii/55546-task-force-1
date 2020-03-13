<?php

namespace frontend\controllers;

use app\models\Task;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;

class SecuredController extends Controller
{
    public function behaviors()
    {
        Yii::$app->user->loginUrl = Url::to('/site/index');

        $user = Yii::$app->user->identity;
        if($user) {
            Yii::$app->db->createCommand("UPDATE user SET last_activity = NOW() WHERE id = :id",
                [':id' => $user->id])->execute();
        }

        Task::updateAll(['status' => Task::STATUS_EXPIRED], "date_end IS NOT NULL AND NOW() > date_end");

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
