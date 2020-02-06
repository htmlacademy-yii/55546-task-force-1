<?php

namespace frontend\controllers;

use app\models\RespondForm;
use app\models\TaskCompletionForm;
use app\models\TaskCreate;
use app\models\TaskRespond;
use common\models\User;
use frontend\components\DebugHelper\DebugHelper;
use frontend\components\SqlAppGenerator\SqlAppGenerator;
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
        $taskUrl = Url::to("/tasks/view/$id");
        $task = Task::find()->with('category', 'author', 'reviewsCount', 'files', 'responds')
            ->where(['id' => (int) $id])->one();
        $user = Yii::$app->user->identity;

        $respondModel = new RespondForm();
        $userRespond = TaskRespond::find()->where("task_id = $task->id AND user_id = $user->id")->one();
        $isRespond = $userRespond ? true : false;

        $taskCompletionModel = new TaskCompletionForm();

        if(!$task) {
            throw new NotFoundHttpException("Страница не найдена!");
        }

        if(Yii::$app->request->post('RespondForm') && !$isRespond) {
            if($respondModel->load(Yii::$app->request->post()) && $respondModel->validate()) {
                $respondModel->createRespond($user->id, $task->id);
                $this->redirect($taskUrl);
            }
        }
        if(Yii::$app->request->post('refusal-btn')) {
            $userRespond->delete();
            $this->redirect($taskUrl);
        }
        if(Yii::$app->request->post('TaskCompletionForm')) {
            if($taskCompletionModel->load(Yii::$app->request->post()) && $taskCompletionModel->validate()) {
                $taskCompletionModel->completionTask($task->id);
                $this->goHome();
            }
        }

        return $this->render('view', [
            'task' => $task,
            'isAuthor' => $user->id === $task->author_id,
            'isExecutor' => $user->getRole() === User::ROLE_EXECUTOR,
            'isRespond' => $isRespond,
            'respondModel' => $respondModel,
            'taskCompletionModel' => $taskCompletionModel,
        ]);
    }

    public function actionDecision(string $status, int $id, int $taskId)
    {
        $task = Task::findOne($taskId);
        $taskRespond = TaskRespond::findOne($id);

        if(Yii::$app->user->identity->id !== $task->author_id) {
            $this->redirect(Url::to("/tasks/view/$taskId"));
        }

        if($status === TaskRespond::STATUS_ACCEPTED) {
            $taskRespond->status = TaskRespond::STATUS_ACCEPTED;
            $task->status = Task::STATUS_EXECUTION;
            $task->save();
        } else {
            $taskRespond->status = TaskRespond::STATUS_DENIED;
        }

        $taskRespond->save();
        $this->redirect(Url::to("/tasks/view/$taskId"));
    }

    public function actionCreate()
    {
        if(Yii::$app->user->identity->getRole() === User::ROLE_EXECUTOR) {
            $this->redirect(Url::to('/tasks'));
        }

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
