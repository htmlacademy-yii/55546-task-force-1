<?php
namespace frontend\controllers;

use app\models\AccountForm;
use app\models\Category;
use frontend\components\DebugHelper\DebugHelper;
use Yii;

class ProfileController extends SecuredController
{
    public function actionIndex()
    {
        $model = new AccountForm();
        if(Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
        }

        return $this->render('index', [
            'user' => Yii::$app->user->identity,
            'model' => $model,
            'categories' => Category::find()->all()
        ]);
    }
}
