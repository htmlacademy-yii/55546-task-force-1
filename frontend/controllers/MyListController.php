<?php
namespace frontend\controllers;

use common\models\User;
use Yii;
use app\models\Task;
use yii\base\ErrorException;
use yii\validators\RangeValidator;
use yii\web\NotFoundHttpException;

/**
 * Контроллер для работы со списком заданий пользователя
 *
 * Class MyListController
 *
 * @package frontend\controllers
 */
class MyListController extends SecuredController
{
    /**
     * Действие для страницы списка заданий пользователя
     *
     * @param string $status строка статуса по которому будут фильтроваться задания
     *
     * @return string
     * @throws ErrorException
     * @throws NotFoundHttpException
     */
    public function actionIndex(string $status = '')
    {
        if(!empty($status) && !(new RangeValidator(['range' => Task::getStatusList()]))->validate($status)) {
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
            $tasks = Task::findAll(['author_id' => $user->id, 'status' => $statusTasks]);
        } elseif($user->identity->role === User::ROLE_EXECUTOR) {
            $tasks = Task::findAll(['executor_id' => $user->id, 'status' => $statusTasks]);
        } else {
            throw new ErrorException('Роль пользователя не определена');
        }

        return $this->render('index', compact('tasks', 'status'));
    }
}
