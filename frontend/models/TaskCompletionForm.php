<?php
namespace app\models;

use frontend\components\DebugHelper;
use yii\base\Model;

class TaskCompletionForm extends Model
{
    const STATUS_DIFFICULT = 'difficult';
    const STATUS_YES = 'yes';

    public $isCompletion;
    public $rating;
    public $text;

    public function rules()
    {
        return [
            ['rating', 'required'],
            ['rating', 'integer'],
            [['isCompletion', 'text', 'rating'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'rating' => 'Оценка',
            'text' => 'Комментарий',
        ];
    }
}
