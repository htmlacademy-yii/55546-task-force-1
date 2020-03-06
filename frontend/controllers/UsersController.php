<?php
namespace frontend\controllers;

use app\models\Category;
use app\models\ExecutorSearchForm;
use common\models\User;
use yii\helpers\ArrayHelper;

class UsersController extends SecuredController
{
    public function actionIndex()
    {
        $model = new ExecutorSearchForm();

        return $this->render('index', [
            'model' => $model,
            'executors' => User::findAll(['role' => User::ROLE_EXECUTOR]),
            'categories' => ArrayHelper::map(Category::find()->all(), 'id', 'title'),
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view');
    }
}
