<?php

namespace frontend\controllers;

use app\models\TaskCreate;
use frontend\components\DebugHelper\DebugHelper;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use app\models\Task;
use app\models\Category;
use yii\web\NotFoundHttpException;
use app\models\TasksFilter;

class TasksController extends SecuredController
{
    public function actionIndex()
    {
        $tasks = Task::find()->where(['status' => Task::STATUS_NEW]);
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

    public function actionCreate()
    {
        $model = new TaskCreate();

        if(Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            if($model->validate() && $model->create(new Task(), Task::STATUS_NEW)) {
                $this->redirect(Url::to('/tasks'));
            }
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => ArrayHelper::map(Category::find()->all(), 'id', 'title'),
        ]);
    }
}
