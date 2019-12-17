<?php

namespace frontend\controllers;

use yii\web\Controller;

class TasksController extends Controller
{
    public function actionIndex()
    {
        return $this->render('browse');
    }
}
