<?php

namespace frontend\modules\v1\controllers;

use app\models\Message;
use app\models\Task;
use common\models\User;
use src\NotificationHelper\NotificationHelper;
use Yii;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;
use yii\base\DynamicModel;
use yii\data\ActiveDataFilter;
use yii\rest\CreateAction;
use yii\web\NotAcceptableHttpException;

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

    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            $version = ArrayHelper::getValue(Yii::$app->response->acceptParams,
                'version', '1.0');

            if (!preg_match('/^\D*1\.0$/', $version)) {
                throw new NotAcceptableHttpException('Use only v1.0 version');
            }
        }

        return true;
    }

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();

        $actions['index']['dataFilter'] = [
            'class' => ActiveDataFilter::class,
            'searchModel' => function () {
                return (new DynamicModel(['task_id' => null]))->addRule('task_id',
                    'integer');
            },
        ];

        return $actions;
    }

    /**
     * @param $action
     * @param $result
     *
     * @return mixed
     */
    public function afterAction($action, $result)
    {
        $handlerResult = parent::afterAction($action, $result);

        if (($action instanceof CreateAction)
            && isset($handlerResult['task_id'])
        ) {
            $task = Task::findOne((int)$result->task_id);
            try {
                NotificationHelper::taskMessage(User::findOne((int)(
                (int)Yii::$app->user->identity->id === (int)$task->author_id ?
                    $task->executor_id : $task->author_id)), $task);
            } catch (\Exception $err) {
                Yii::warning('Mail notification not sended');
            }
        }

        return $handlerResult;
    }
}
