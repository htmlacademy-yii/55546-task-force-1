<?php

namespace frontend\controllers;

use yii\web\Controller;

use app\models\Task;
use app\models\Category;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $tasks = Task::find()
            ->select('task.*, category.title as category, category.code as category_code, user_data.address')
            ->innerJoin('category', '`category`.`id` = category_id')
            ->innerJoin('user_data', '`user_data`.`user_id` = author_id')
            ->where(['task.status' => 'false'])
            ->orderBy('task.date_start DESC')
            ->asArray()
            ->all();

        return $this->render('index', [
            'tasks' => $tasks ?? [],
            'categories' => Category::find()->asArray()->all()
        ]);
    }
}
