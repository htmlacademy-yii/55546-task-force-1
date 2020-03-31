<?php

namespace frontend\controllers;

use app\models\RespondForm;
use app\models\Review;
use app\models\TaskCompletionForm;
use app\models\TaskCreate;
use app\models\TaskFile;
use app\models\TaskRespond;
use common\models\User;
use frontend\src\NotificationHelper\NotificationHelper;
use PHPUnit\Framework\Error\Error;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use app\models\Task;
use app\models\Category;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;
use app\models\TasksFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * Контроллер для работы с заданиями
 *
 * Class TasksController
 *
 * @package frontend\controllers
 */
class TasksController extends SecuredController
{
    /** @var string строка с адресом директории для хранения файлов к заданиям */
    public $tasksPath = '';

    /**
     * Определением фильтра
     *
     * @return array
     * @throws \yii\db\Exception
     */
    public function behaviors()
    {
        $rules = parent::behaviors();

        $rule = [
            'allow' => false,
            'actions' => [
                'create',
            ],
            'matchCallback' => function ($rule, $action) {
                $user = Yii::$app->user->identity;

                return $user && $user->role === User::ROLE_EXECUTOR;
            },
            'denyCallback' => function ($rule, $action) {
                return $action->controller->redirect(Task::getBaseTasksUrl());
            },
        ];

        array_unshift($rules['access']['rules'], $rule);

        return $rules;
    }

    /**
     * Действие для страницы списка заданий
     *
     * @return string шаблон с данными страницы
     */
    public function actionIndex()
    {
        $tasks = Task::find()->where(['status' => Task::STATUS_NEW]);
        $taskModel = new TasksFilter();
        if (Yii::$app->request->get('TasksFilter')) {
            $taskModel->load(Yii::$app->request->get());
        } elseif (!empty(Yii::$app->request->queryParams['filter'])) {
            $filter
                = ['TasksFilter' => Yii::$app->request->queryParams['filter']];
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
                    'date_start' => SORT_DESC,
                ],
            ],
        ]);

        return $this->render('index', [
            'provider' => $provider,
            'taskModel' => $taskModel,
            'categories' => Category::getCategoriesArray(),
            'period' => TasksFilter::PERIOD_LIST,
        ]);
    }

    /**
     * Действие для страницы просмотра задания
     *
     * @param int $id идентификатор задания
     *
     * @return string шаблон с данными страницы
     * @throws NotFoundHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionView(int $id)
    {
        $task = Task::findOne($id);
        $user = Yii::$app->user->identity;
        if (!$task) {
            throw new NotFoundHttpException("Страница не найдена!");
        }

        return $this->render('view', [
            'task' => $task,
            'taskLocation' => $task->getLocation(),
            'isAuthor' => $user->id === $task->author_id,
            'isExecutor' => $user->role === User::ROLE_EXECUTOR,
            'isSelectedExecutor' => $task->executor_id === $user->id,
            'executor' => $task->executor,
            'isRespond' => TaskRespond::find()->where([
                'task_id' => $task->id,
                'user_id' => $user->id,
            ])->exists(),
            'respondModel' => new RespondForm(),
            'taskCompletionModel' => new TaskCompletionForm(),
        ]);
    }

    /**
     * Действие для ajax запроса на валидацию формы отклика к заданию
     *
     * @return array
     */
    public function actionRespondAjaxValidation()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $respondModel = new RespondForm();
            $respondModel->setAttributes(Yii::$app->request->post('RespondForm'));

            return ActiveForm::validate($respondModel);
        }
    }

    /**
     * Действие для завершения заказчиком задания
     *
     * @param int $taskId идентификатор задания
     *
     * @return Response
     */
    public function actionCompletion(int $taskId)
    {
        $model = new TaskCompletionForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $task = Task::findOne($taskId);
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $executor = User::findOne((int)$task->executor_id);
                if ($model->isCompletion === TaskCompletionForm::STATUS_YES) {
                    $task->status = Task::STATUS_COMPLETED;
                    $executor->userData->updateCounters(['success_counter' => 1]);
                } else {
                    $task->status = Task::STATUS_FAILING;
                    $executor->userData->updateCounters(['failing_counter' => 1]);
                }
                $task->save();

                (new Review([
                    'text' => $model->text,
                    'rating' => $model->rating,
                    'task_id' => $task->id,
                    'author_id' => $task->author_id,
                    'executor_id' => $task->executor_id,
                ]))->save();

                $queryRating
                    = Yii::$app->db->createCommand("SELECT SUM(rating) as `rating`, COUNT(id) as `count` FROM review WHERE executor_id = :id",
                    [':id' => $executor->id])->queryOne();
                $executor->userData->rating = (string)round((($queryRating['rating']
                        + $model->rating) / ($queryRating['count'] + 1)), 1);
                $executor->userData->save();

                if ($executor->userNotifications->is_task_actions) {
                    NotificationHelper::taskComplete($executor, $task);
                }
                $transaction->commit();
            } catch (\Exception $err) {
                $transaction->rollBack();
            }
        }

        return $this->redirect(Task::getBaseTasksUrl());
    }

    /**
     * Действие для отказа исполнителем от задания
     *
     * @param int $taskId идентификатор задания
     *
     * @return Response
     * @throws \Throwable
     */
    public function actionRefusal(int $taskId)
    {
        $user = Yii::$app->user->identity;
        $task = Task::findOne($taskId);
        if ($respond = TaskRespond::findOne([
            'task_id' => $task->id,
            'user_id' => $user->id,
        ])
        ) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $respond->delete();

                $task->status = Task::STATUS_NEW;
                $task->executor_id = null;
                $task->save();
                $user->userData->updateCounters(['failing_counter' => 1]);
                $user->userData->save();

                $authorTask = User::findOne((int)$task->author_id);
                if ($authorTask->userNotifications->is_task_actions) {
                    NotificationHelper::taskDenial($authorTask, $task);
                }
                $transaction->commit();
            } catch (\Exception $err) {
                $transaction->rollBack();
            }
        }

        return $this->redirect(Task::getBaseTasksUrl());
    }

    /**
     * Действие для отлика исполнителем на задание
     *
     * @param int $taskId идентификатор задания
     *
     * @return Response
     */
    public function actionRespond(int $taskId)
    {
        $model = new RespondForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $task = Task::findOne($taskId);
            (new TaskRespond([
                'user_id' => Yii::$app->user->identity->id,
                'task_id' => $task->id,
                'text' => $model->text,
                'price' => $model->price,
                'status' => TaskRespond::STATUS_NEW,
                'public_date' => date("Y-m-d h:i:s"),
            ]))->save();

            $authorTask = User::findOne((int)$task->author_id);
            if ($authorTask->userNotifications->is_task_actions) {
                NotificationHelper::taskRespond($authorTask, $task);
            }
        }

        return $this->redirect(Task::getBaseTasksUrl());
    }

    /**
     * Действие для обработки завершения задания
     *
     * @param int    $respondId идентификатор отклика к заданию
     * @param string $status    статус выполненности задания
     *
     * @return Response
     */
    public function actionDecision(int $respondId, string $status)
    {
        $taskRespond = TaskRespond::findOne($respondId);
        $task = Task::findOne($taskRespond->task_id);
        $taskUrl = $task->getCurrentTaskUrl();

        if (Yii::$app->user->identity->id !== $task->author_id) {
            $this->redirect($taskUrl);
        }

        if ($status === TaskRespond::STATUS_ACCEPTED) {
            $taskRespond->status = TaskRespond::STATUS_ACCEPTED;
            $task->status = Task::STATUS_EXECUTION;
            $task->executor_id = $taskRespond->user_id;
            $task->save();

            $executorTask = User::findOne((int)$taskRespond->user_id);
            if ($executorTask->userNotifications->is_task_actions) {
                NotificationHelper::taskStart($executorTask, $task);
            }
        } else {
            $taskRespond->status = TaskRespond::STATUS_DENIED;
        }

        $taskRespond->save();

        return $this->redirect($taskUrl);
    }

    /**
     * Действие для отмены задания создателем
     *
     * @param int $taskId идентификатор задания
     *
     * @return void|Response
     */
    public function actionCancel(int $taskId)
    {
        $task = Task::findOne($taskId);
        if (!$task || $task->author_id !== Yii::$app->user->id
            || $task->status !== Task::STATUS_NEW
        ) {
            return;
        }

        $task->status = Task::STATUS_CANCELED;
        $task->save();

        return $this->redirect(Task::getBaseTasksUrl());
    }

    /**
     * Действие для создания новго задания
     *
     * @return string
     * @throws \yii\base\ErrorException
     * @throws \yii\db\Exception
     * @throws \yii\web\ServerErrorHttpException
     */
    public function actionCreate()
    {
        $model = new TaskCreate();
        if (Yii::$app->request->isPost
            && $files = UploadedFile::getInstancesByName('files')
        ) {
            $model->files = $files;
        }
        if (Yii::$app->request->post()
            && $model->load(Yii::$app->request->post())
            && $model->validate()
        ) {
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

            if ($model->files) {
                $pathTaskDir = "$this->tasksPath/$task->id";
                if (file_exists($pathTaskDir)) {
                    FileHelper::removeDirectory($pathTaskDir);
                }
                mkdir($pathTaskDir);
                (new TaskFile(['path' => $pathTaskDir]))->setFiles($task->id,
                    $model->files);
            }
            $this->redirect(Task::getBaseTasksUrl());
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => Category::getCategoriesArray(),
        ]);
    }

    /**
     * Действие для ajax запроса на получение списка локаций
     *
     * @param string $place строка с названием локации для поиска
     *
     * @return mixed список найденных локаций
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionAjaxGetYandexPlace(string $place = '')
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return json_decode(Yii::$container->get('yandexMap')
            ->getDataMap($place))
            ->response->GeoObjectCollection->featureMember;
    }
}
