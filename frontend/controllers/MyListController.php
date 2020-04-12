<?php

namespace frontend\controllers;

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
        if (!$status = Task::getStatusByCategory($category)) {
            throw new NotFoundHttpException('Страница не найдена!');
        }

        $user = Yii::$app->user;
        $tasks = Task::findAll([
            $user->identity->getIsExecutor() ? 'executor_id'
                : 'author_id' => $user->id,
            'status' => $status,
        ]);

        return $this->render('index', compact('tasks', 'category'));
    }
}
