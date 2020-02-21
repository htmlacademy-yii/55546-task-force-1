<?php
namespace frontend\modules\api\controllers;

use app\models\Message;
use yii\rest\ActiveController;
use yii\base\DynamicModel;
use yii\data\ActiveDataFilter;

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
}
