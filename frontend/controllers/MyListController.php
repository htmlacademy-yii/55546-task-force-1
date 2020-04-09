<?php

namespace frontend\controllers;

use common\models\User;
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
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionIndex(string $category = '')
    {
        if (!$status = Task::getStatusByCategory($category)) {
            throw new NotFoundHttpException('Страница не найдена!');
        }

        $user = Yii::$app->user;
        $tasks = Task::findAll([
            $user->identity->role === User::ROLE_CLIENT ? 'author_id'
                : 'executor_id' => $user->id,
            'status' => $status,
        ]);

        return $this->render('index', compact('tasks', 'category'));
    }
}
