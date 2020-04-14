<?php

namespace frontend\modules\v1\controllers;

use app\models\Message;
use app\models\Task;
use src\TaskHelper\TaskHelper;
use Yii;
use yii\rest\ActiveController;

/**
 * Контроллер для работы со списком сообщений к заданиям
 *
 * Class MessageController
 *
 * @package frontend\modules\api\controllers
 */
class MessageController extends ActiveController
{
    /** @var string строка с указанием класса модели */
    public $modelClass = Message::class;

    /**
     * Переопределение действий для контроллера
     *
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['create']);

        return $actions;
    }

    /**
     * Получени списка сообщений для указанного задания
     *
     * @param int $id идентификатор задания
     *
     * @return array список сообщений
     */
    public function actionIndex(int $id): array
    {
        return $this->modelClass::findAll(['task_id' => $id]);
    }

    /**
     * Добавленние нового сообщения для указанного задания
     *
     * @param int $id идентификатор задания
     *
     * @return array|null данные нового задания
     */
    public function actionCreate(int $id): ?array
    {
        $userId = Yii::$app->user->identity->id;
        $task = Task::findOne($id);
        $message = json_decode(Yii::$app->getRequest()->getRawBody(), true);
        if (!$message || !$task
            || !$task->getAccessCheckMessageCreate($userId)
        ) {
            return null;
        }

        Yii::$app->response->statusCode = 201;

        return TaskHelper::message($task, new $this->modelClass([
            'message' => $message['message'],
            'published_at' => date('Y-m-d h:i:s'),
            'is_mine' => $task->getIsAuthor($userId),
            'task_id' => $id,
        ]));
    }
}
