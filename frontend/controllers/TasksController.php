<?php

namespace frontend\controllers;

use frontend\components\DebugHelper\DebugHelper;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\Task;
use app\models\Category;
use yii\web\NotFoundHttpException;
use app\models\TasksFilter;

class TasksController extends SecuredController
{
    public function actionIndex()
    {
        $tasks = Task::find()->where(['status' => false]);
        $taskModel = new TasksFilter();

        if(Yii::$app->request->isPost) {
            $taskModel->load(Yii::$app->request->post());
            $taskModel->applyFilters($tasks);
        }

        return $this->render('index', [
            'tasks' => $tasks->with(['category', 'author'])->orderBy('date_start DESC')->all(),
            'taskModel' => $taskModel,
            'categories' => Category::find()->all(),
            'period' => TasksFilter::PERIOD_LIST,
        ]);
    }

    public function actionView($id)
    {
        $task = Task::find()->with('category', 'author', 'reviewsCount', 'files', 'responds')
            ->where(['id' => (int) $id])->one();

        if(!$task) {
            throw new NotFoundHttpException("Страница не найдена!");
        }

        return $this->render('view', compact('task'));
    }
}
