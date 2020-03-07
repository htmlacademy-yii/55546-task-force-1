<?php

namespace frontend\controllers;

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
            $user->last_activity = date("Y-m-d h:i:s");
            $user->save();
        }

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
