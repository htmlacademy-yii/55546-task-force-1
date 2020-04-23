<?php

namespace frontend\controllers;

use src\TaskHelper\TaskHelper;
use Yii;
use app\models\Task;
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
     * @param string $category строка статуса по которому будут фильтроваться задания
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex(string $category = ''): string
    {
        if (!$status = TaskHelper::getStatusByCategoryTask($category)) {
            throw new NotFoundHttpException('Страница не найдена!');
        }

        $user = Yii::$app->user;
        $tasks = Task::find()
            ->where(['status' => $status])
            ->andWhere('executor_id = :executor_id OR author_id = :author_id', [
                ':executor_id' => $user->id,
                ':author_id' => $user->id,
            ])->all();

        return $this->render('index', compact('tasks', 'category'));
    }
}
