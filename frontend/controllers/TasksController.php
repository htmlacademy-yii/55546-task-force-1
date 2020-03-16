<?php
namespace frontend\controllers;

use app\models\RespondForm;
use app\models\Review;
use app\models\TaskCompletionForm;
use app\models\TaskCreate;
use app\models\TaskFile;
use app\models\TaskRespond;
use common\models\User;
use frontend\components\NotificationHelper\NotificationHelper;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use app\models\Task;
use app\models\Category;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;
use app\models\TasksFilter;
use yii\web\Response;
use yii\web\UploadedFile;

class TasksController extends SecuredController
{
    public $tasksPath = '';

    public function behaviors()
    {
        $rules = parent::behaviors();
        $rule = [
            'allow' => false,
            'actions' => [
                'create'
            ],
            'matchCallback' => function($rule, $action) {
                $user = Yii::$app->user->identity;
                return $user && $user->role === User::ROLE_EXECUTOR;
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
        if(Yii::$app->request->get('TasksFilter')) {
            $taskModel->load(Yii::$app->request->get());
        } else if(!empty(Yii::$app->request->queryParams['filter'])) {
            $filter = ['TasksFilter' => Yii::$app->request->queryParams['filter']];
            $taskModel->load($filter);
        }
        $taskModel->applyFilters($tasks);

        $provider = new ActiveDataProvider([
            'query' => $tasks->with(['category', 'author']),
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort' => [
                'defaultOrder' => [
                    'date_start' => SORT_DESC
                ]
            ],
        ]);
        return $this->render('index', [
            'provider' => $provider,
            'taskModel' => $taskModel,
            'categories' => ArrayHelper::map(Category::find()->all(), 'id', 'title'),
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
        $userRespond = TaskRespond::findOne(['task_id' => $task->id, 'user_id' => $user->id]);
        $isRespond = $userRespond ? true : false;
        $taskCompletionModel = new TaskCompletionForm();

        if(!$task) {
            throw new NotFoundHttpException("Страница не найдена!");
        }

        if(Yii::$app->request->post('RespondForm') && !$isRespond) {
            if($respondModel->load(Yii::$app->request->post()) && $respondModel->validate()) {
                (new TaskRespond([
                    'user_id' => $user->id,
                    'task_id' => $task->id,
                    'text' => $respondModel->text,
                    'price' => $respondModel->price,
                    'status' => TaskRespond::STATUS_NEW,
                    'public_date' => date("Y-m-d h:i:s"),
                ]))->save();

                $authorTask = User::findOne((int) $task->author_id);
                // новый отклик к заданию, отправка события заказчику задания
                if($authorTask->userNotifications->is_task_actions) {
                    NotificationHelper::taskRespond($authorTask, $task);
                }

                $this->redirect($taskUrl);
            }
        }
        if(Yii::$app->request->post('refusal-btn')) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $userRespond->delete();

                $task->status = Task::STATUS_FAILING;
                $task->save();
                $user->userData->failing_counter = (int) $user->userData->failing_counter + 1;
                $user->userData->save();

                $authorTask = User::findOne((int) $task->author_id);
                // отказ от задания исполнителем, отправка события заказчику
                if($authorTask->userNotifications->is_task_actions) {
                    NotificationHelper::taskDenial($authorTask, $task);
                }
                $transaction->commit();
            } catch (\Exception $err) {
                $transaction->rollBack();
            }

            $this->redirect($taskUrl);
        }
        if(Yii::$app->request->post('TaskCompletionForm')) {
            if($taskCompletionModel->load(Yii::$app->request->post()) && $taskCompletionModel->validate()) {
                $transaction = Yii::$app->db->beginTransaction();

                try {
                    $executor = User::findOne((int) $task->executor_id);
                    if($taskCompletionModel->isCompletion === TaskCompletionForm::STATUS_YES) {
                        $task->status = Task::STATUS_COMPLETED;
                        $executor->userData->success_counter = (int) $executor->userData->success_counter + 1;
                    } else {
                        $task->status = Task::STATUS_FAILING;
                        $executor->userData->failing_counter = (int) $executor->userData->failing_counter + 1;
                    }

                    (new Review([
                        'text' => $taskCompletionModel->text,
                        'rating' => $taskCompletionModel->rating,
                        'task_id' => $task->id,
                        'author_id' => $task->author_id,
                        'executor_id' => $task->executor_id,
                    ]))->save();

                    $queryRating = Yii::$app->db->createCommand("SELECT AVG(rating) as rating FROM review WHERE executor_id = {$executor->id}")->queryOne();
                    $executor->userData->rating = round($queryRating['rating'], 1);

                    $task->save();
                    $executor->userData->save();

                    // завершение задания, отправка события исполнителю
                    if($executor->userNotifications->is_task_actions) {
                        NotificationHelper::taskComplete($executor, $task);
                    }
                    $transaction->commit();
                } catch (\Exception $err) {
                    $transaction->rollBack();
                }

                $this->redirect($taskUrl);
            }
        }

        return $this->render('view', [
            'task' => $task,
            'taskLocation' => $task->getLocation(),
            'isAuthor' => $user->id === $task->author_id,
            'isExecutor' => $user->role === User::ROLE_EXECUTOR,
            'isSelectedExecutor' => $task->executor_id === $user->id,
            'executor' => $task->executor,
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

    // действия автора задания с откликаками на задания
    public function actionDecision(string $status, int $id, int $taskId)
    {
        $task = Task::findOne($taskId);
        $taskUrl = $task->getCurrentTaskUrl();
        $taskRespond = TaskRespond::findOne($id);

        // если действие пытается совершить не автор задания, то происходит редирект
        if(Yii::$app->user->identity->id !== $task->author_id) {
            $this->redirect($taskUrl);
        }

        // принятие отклика задания
        if($status === TaskRespond::STATUS_ACCEPTED) {
            $taskRespond->status = TaskRespond::STATUS_ACCEPTED;
            $task->status = Task::STATUS_EXECUTION;
            $task->executor_id = $taskRespond->user_id;
            $task->save();

            // отправка уведомления исполнителю о принятии его отклика
            $executorTask = User::findOne((int) $taskRespond->user_id);
            if($executorTask->userNotifications->is_task_actions) {
                NotificationHelper::taskStart($executorTask, $task);
            }
        } else { // отклонение отклика задания
            $taskRespond->status = TaskRespond::STATUS_DENIED;
        }

        $taskRespond->save();
        $this->redirect($taskUrl);
    }

    public function actionCancel(int $taskId)
    {
        $task = Task::findOne($taskId);
        if(!$task || $task->author_id !== Yii::$app->user->id || $task->status !== Task::STATUS_NEW) {
            return;
        }

        $task->status = Task::STATUS_CANCELED;
        $task->save();

        return $this->redirect(Task::getBaseTasksUrl());
    }

    public function actionCreate()
    {
        $model = new TaskCreate();

        if(Yii::$app->request->isPost && $files = UploadedFile::getInstancesByName('files')) {
            $model->files = $files;
        }

        if(Yii::$app->request->post() && $model->load(Yii::$app->request->post()) && $model->validate()) {
            $userId = Yii::$app->user->identity->id;
            $task = new Task([
                'author_id' => $userId,
                'title' => $model->title,
                'description' => $model->description,
                'category_id' => $model->categoryId,
                'price' => $model->price,
                'latitude' => $model->latitude,
                'longitude' => $model->longitude,
                'date_end' => $model->dateEnd,
                'city_id' => $model->cityId,
                'date_start' => date("Y-m-d h:i:s"),
                'status' => Task::STATUS_NEW,
            ]);
            $task->save();

            if($model->files) {
                $pathTaskDir = "$this->tasksPath/$task->id";
                if(file_exists($pathTaskDir)) {
                    FileHelper::removeDirectory($pathTaskDir);
                }
                mkdir($pathTaskDir);
                (new TaskFile(['path' => $pathTaskDir]))->setFiles($task->id, $model->files);
            }

            $this->redirect(Task::getBaseTasksUrl());
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
