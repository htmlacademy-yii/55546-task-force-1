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
        $tasks = Task::find()->where(['status' => false]);

        if($filters) {
            if(isset($filters['category'])) {
                $tasks->andWhere("`category_id` IN (" . implode(',', array_keys($filters['category'])) . ")");
            }
            if(isset($filters['is-no-executor'])) {
                $tasks->andWhere("`executor_id` IS NULL");
            }
            if(isset($filters['is-telework'])) {
                $tasks->andWhere(['is_telework' => true]);
            }
            if(isset($filters['title'])) {
                $tasks->andWhere(['like', 'title', $filters['title']]);
            }
        }

        return $this->render('index', [
            'tasks' => $tasks->with(['category', 'author'])->orderBy('date_start DESC')->all(),
            'filters' => Yii::$app->request->post('filters'),
            'categories' => Category::find()->all(),
        ]);
    }
}
