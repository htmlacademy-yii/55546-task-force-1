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
            'tasks' => Task::find()->where(['task.status' => 'false'])
                ->orderBy('task.date_start DESC')->all(),
            'categories' => Category::find()->asArray()->all()
        ]);
    }
}
