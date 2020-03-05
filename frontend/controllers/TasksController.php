<?php
namespace frontend\controllers;

use app\models\RespondForm;
use app\models\TaskCompletionForm;
use app\models\TaskCreate;
use app\models\TaskRespond;
use common\models\User;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Task;
use app\models\Category;
use yii\web\NotFoundHttpException;
use app\models\TasksFilter;
use yii\web\Response;

class TasksController extends SecuredController
{
    public function behaviors()
    {
        $rules = parent::behaviors();
        $rule = [
            'allow' => false,
            'actions' => [
                'create'
            ],
            'matchCallback' => function($rule, $action) {
                return Yii::$app->user->identity->getRole() === User::ROLE_EXECUTOR;
            },
            'denyCallback' => function($rule, $action) {
                return $action->controller->redirect(Task::getBaseTasksUrl());
            },
        ];

        array_unshift($rules['access']['rules'], $rule);
        return $rules;
    }

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

    public function actionView(int $id)
    {
        $task = Task::find()->with('category', 'author', 'files', 'responds')
            ->where(['id' => (int) $id])->one();
        $taskUrl = $task->getCurrentTaskUrl();
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
            'taskLocation' => $task->getLocation(),
            'isAuthor' => $user->id === $task->author_id,
            'isExecutor' => $user->getRole() === User::ROLE_EXECUTOR,
            'isRespond' => $isRespond,
            'respondModel' => $respondModel,
            'taskCompletionModel' => $taskCompletionModel
        ]);
    }

    public function actionRespondAjaxValidation()
    {
        if(Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $respondModel = new RespondForm();
            $respondModel->setAttributes(Yii::$app->request->post('RespondForm'));
            return ActiveForm::validate($respondModel);
        }
    }

    public function actionDecision(string $status, int $id, int $taskId)
    {
        $task = Task::findOne($taskId);
        $taskUrl = $task->getCurrentTaskUrl();
        $taskRespond = TaskRespond::findOne($id);

        if(Yii::$app->user->identity->id !== $task->author_id) {
            $this->redirect($taskUrl);
        }

        if($status === TaskRespond::STATUS_ACCEPTED) {
            $taskRespond->status = TaskRespond::STATUS_ACCEPTED;
            $task->status = Task::STATUS_EXECUTION;
            $task->save();
        } else {
            $taskRespond->status = TaskRespond::STATUS_DENIED;
        }

        $taskRespond->save();
        $this->redirect($taskUrl);
    }

    public function actionCreate()
    {
        $model = new TaskCreate();
        if(Yii::$app->request->post()) {
            $model->load(Yii::$app->request->post());
            if($model->validate() && $model->create(new Task(), Task::STATUS_NEW)) {
                $this->redirect(Task::getBaseTasksUrl());
            }
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => ArrayHelper::map(Category::find()->all(), 'id', 'title'),
        ]);
    }

    public function actionAjaxGetYandexPlace(string $place = '')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return json_decode(Yii::$container->get('yandexMap')->getDataMap($place))
            ->response->GeoObjectCollection->featureMember;
    }
}
