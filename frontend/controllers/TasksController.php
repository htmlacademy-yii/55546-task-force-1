<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use app\models\Task;
use app\models\Category;

class TasksController extends Controller
{
    public function actionIndex()
    {
        $filters = Yii::$app->request->post('filters');
        $tasks = Task::find()->where(['status' => 'false']);


        /*

        (
            [is-telework] => on
            [time] => month
            [title] => выаываыв
        )

         * */


        if($filters) {
            if(isset($filters['category'])) {
                $tasks->andWhere("`category_id` IN (" . implode(',', array_keys($filters['category'])) . ")");
            }
            if(isset($filters['is-no-executor'])) {
                $tasks->andWhere("`executor_id` IS NULL");
            }
        }

        return $this->render('index', [
            'tasks' => $tasks->with(['category', 'author'])->orderBy('date_start DESC')->all(),
            'filters' => Yii::$app->request->post('filters'),
            'categories' => Category::find()->all(),
        ]);
    }
}
