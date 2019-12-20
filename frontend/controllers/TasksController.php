<?php

namespace frontend\controllers;

use yii\web\Controller;

use app\models\Task;
use app\models\Category;

class TasksController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index', [
            'tasks' => Task::find()->where(['status' => 'false'])
                ->orderBy('date_start DESC')->all(),
            'categories' => Category::find()->all()
        ]);
    }
}
