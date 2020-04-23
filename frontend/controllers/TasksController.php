<?php

namespace frontend\controllers;

use app\models\RespondForm;
use app\models\TaskCompletionForm;
use app\models\TaskCreate;
use app\models\TaskRespond;
use src\ActionTaskHelper\ActionTaskHelper;
use src\UrlHelper\UrlHelper;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use app\models\Task;
use app\models\Category;
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
     */
    public function behaviors(): array
    {
        $rules = parent::behaviors();

        $rule = [
            'allow' => false,
            'actions' => [
                'create',
            ],
            'matchCallback' => function ($rule, $action) {
                $user = Yii::$app->user->identity;

                return $user && $user->getIsExecutor();
            },
            'denyCallback' => function ($rule, $action) {
                return $action->controller->redirect(UrlHelper::getBaseTasksUrl());
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
    public function actionIndex(): string
    {
        $tasks = Task::find()->where(['status' => Task::STATUS_NEW]);
        $taskModel = new TasksFilter();
        if (Yii::$app->request->get('TasksFilter')) {
            $taskModel->load(Yii::$app->request->get());
        } elseif (!empty(Yii::$app->request->queryParams['filter'])) {
            $taskModel->load(['TasksFilter' => Yii::$app->request->queryParams['filter']]);
        }

        if ($taskModel->validate()) {
            $taskModel->applyFilters($tasks);
        }

        $provider = new ActiveDataProvider([
            'query' => $tasks->with(['category', 'author', 'executor']),
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
     */
    public function actionView(int $id): string
    {
        if (!$task = Task::findOne($id)) {
            throw new NotFoundHttpException('Страница не найдена!');
        }

        return $this->render('view', [
            'task' => $task,
            'user' => Yii::$app->user->identity,
            'isAuthor' => $task->getIsAuthor(Yii::$app->user->id),
            'respondModel' => new RespondForm(),
            'taskCompletionModel' => new TaskCompletionForm(),
            'completionYes' => TaskCompletionForm::STATUS_YES,
            'completionDifficult' => TaskCompletionForm::STATUS_DIFFICULT,
        ]);
    }

    /**
     * Действие для ajax запроса на валидацию формы отклика к заданию
     *
     * @return array|null
     */
    public function actionRespondAjaxValidation(): ?array
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $respondModel = new RespondForm();
            $respondModel->setAttributes(Yii::$app->request->post('RespondForm'));

            return ActiveForm::validate($respondModel);
        }

        return null;
    }

    /**
     * Действие для завершения заказчиком задания
     *
     * @param int $taskId идентификатор задания
     *
     * @return Response
     */
    public function actionCompletion(int $taskId): Response
    {
        $model = new TaskCompletionForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()
            && $task = Task::findOne($taskId)
        ) {
            ActionTaskHelper::completion($task, $model);
        }

        return $this->redirect(UrlHelper::getBaseTasksUrl());
    }

    /**
     * Действие для отказа исполнителем от задания
     *
     * @param int $taskId идентификатор задания
     *
     * @return Response
     * @throws \Throwable
     */
    public function actionRefusal(int $taskId): Response
    {
        $task = Task::findOne($taskId);
        if ($task
            && $respond = TaskRespond::findOne([
                'task_id' => $task->id,
                'user_id' => Yii::$app->user->id,
            ])
        ) {
            ActionTaskHelper::refusal($task, $respond);
        }

        return $this->redirect(UrlHelper::getBaseTasksUrl());
    }

    /**
     * Действие для отлика исполнителем на задание
     *
     * @param int $taskId идентификатор задания
     *
     * @return Response
     */
    public function actionRespond(int $taskId): Response
    {
        $model = new RespondForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()
            && $task = Task::findOne($taskId)
        ) {
            ActionTaskHelper::respond($task, $model);
        }

        return $this->redirect(UrlHelper::getBaseTasksUrl());
    }

    /**
     * Действие для обработки завершения задания
     *
     * @param int    $respondId идентификатор отклика к заданию
     * @param string $status    статус выполненности задания
     *
     * @return Response
     */
    public function actionDecision(int $respondId, string $status): Response
    {
        if (!$respond = TaskRespond::findOne($respondId)) {
            return $this->redirect(UrlHelper::getBaseTasksUrl());
        }

        $task = Task::findOne($respond->task_id);
        if (Yii::$app->user->identity->id === $task->author_id) {
            ActionTaskHelper::decision($task, $respond, $status);
        }

        return $this->redirect(UrlHelper::createTaskUrl($task->id));
    }

    /**
     * Действие для отмены задания создателем
     *
     * @param int $taskId идентификатор задания
     *
     * @return void|Response
     */
    public function actionCancel(int $taskId): ?Response
    {
        $task = Task::findOne($taskId);
        if (!$task || $task->author_id !== Yii::$app->user->id
            || $task->status !== Task::STATUS_NEW
        ) {
            return null;
        }

        $task->actionCancel();

        return $this->redirect(UrlHelper::getBaseTasksUrl());
    }

    /**
     * Действие для создания новго задания
     *
     * @return string
     */
    public function actionCreate(): string
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
            && Task::create($model, $this->tasksPath)
        ) {
            $this->redirect(UrlHelper::getBaseTasksUrl());
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
     * @return array|null список найденных локаций
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionAjaxGetYandexPlace(string $place = ''): ?array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($content = json_decode(Yii::$container->get('yandexMap')
            ->getDataMap($place))
        ) {
            return $content->response->GeoObjectCollection->featureMember;
        }

        return null;
    }
}
