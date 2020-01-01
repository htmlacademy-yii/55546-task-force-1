<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use app\models\Task;
use app\models\Category;
use app\models\TasksFilter;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $filters = Yii::$app->request->post('filters');
        $tasks = Task::find()->where(['status' => false]);
        $taskModel = new TasksFilter($filters, $tasks);

        if(Yii::$app->request->isPost) {
            $tasks = $taskModel->applyFilters();
        }

        return $this->render('index', [
            'tasks' => $tasks->with(['category', 'author'])->orderBy('date_start DESC')->all(),
            'taskModel' => $taskModel,
            'filters' => $filters,
            'categories' => Category::find()->all(),
        ]);
    }
}
