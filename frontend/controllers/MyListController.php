<?php
namespace frontend\controllers;

use app\models\TaskRespond;
use common\models\User;
use Yii;
use app\models\Task;
use frontend\components\DebugHelper\DebugHelper;
use yii\base\ErrorException;
use yii\web\NotFoundHttpException;

class MyListController extends SecuredController
{
    public function actionIndex(string $status = '')
    {
        if(!empty($status) && !in_array($status, Task::getStatusList())) {
            throw new NotFoundHttpException("Страница не найдена!");
        }

        $user = Yii::$app->user;
        $statusTasks = Task::getStatusList();
        if(!empty($status)) {
            $statusTasks = ($status === Task::STATUS_CANCELED || $status === Task::STATUS_FAILING) ?
                [Task::STATUS_CANCELED, Task::STATUS_FAILING] : $status;
        }

        $tasks = [];
        if($user->identity->role === User::ROLE_CLIENT) {
            // для заказчика
            $tasks = Task::findAll(['author_id' => $user->id, 'status' => $statusTasks]);
        } elseif($user->identity->role === User::ROLE_EXECUTOR) {
            // для исполнителя
            $respondsTask = TaskRespond::find()->select('task_id')
                ->where(['user_id' => $user->id, 'status' => TaskRespond::STATUS_ACCEPTED])->asArray()->column();
            $tasks = Task::findAll(['id' => $respondsTask, 'status' => $statusTasks]);
        } else {
            throw new ErrorException('Роль пользователя не определена');
        }

        return $this->render('index', compact('tasks', 'status'));
    }
}
