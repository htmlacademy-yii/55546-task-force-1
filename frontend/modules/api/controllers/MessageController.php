<?php
namespace frontend\modules\api\controllers;

use app\models\Message;
use Yii;
use yii\rest\ActiveController;
use yii\web\Response;

class MessageController extends ActiveController
{
    public $modelClass = Message::class;
}
