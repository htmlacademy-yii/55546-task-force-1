<?php
namespace frontend\modules\api\controllers;

use app\models\Message;
use app\models\Task;
use common\models\User;
use frontend\components\NotificationHelper\NotificationHelper;
use Yii;
use yii\rest\ActiveController;
use yii\base\DynamicModel;
use yii\data\ActiveDataFilter;
use yii\rest\CreateAction;

class MessageController extends ActiveController
{
    public $modelClass = Message::class;

    public function actions()
    {
        $actions = parent::actions();

        $actions['index']['dataFilter'] = [
            'class' => ActiveDataFilter::class,
            'searchModel' => function() {
                return (new DynamicModel(['task_id' => null]))->addRule('task_id', 'integer');
            }
        ];

        return $actions;
    }

    public function afterAction($action, $result)
    {
        $handlerResult = parent::afterAction($action, $result);

        if (($action instanceof CreateAction) && isset($handlerResult['task_id'])) {
            $task = Task::findOne((int) $result->task_id);
            try {
                NotificationHelper::taskMessage(User::findOne((int) (
                    (int) Yii::$app->user->identity->id === (int) $task->author_id ?
                        $task->executor_id : $task->author_id)), $task);
            } catch (\Exception $err) {
                Yii::warning('Mail notification not sended');
            }
        }

        return $handlerResult;
    }
}
