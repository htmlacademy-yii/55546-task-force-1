<?php

namespace frontend\controllers;

use frontend\components\DebugHelper\DebugHelper;
use yii\web\Controller;

use app\models\Task;
use app\models\Category;
use yii\web\NotFoundHttpException;

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
