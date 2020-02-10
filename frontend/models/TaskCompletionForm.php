<?php
namespace app\models;

use frontend\components\DebugHelper\DebugHelper;
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

    public function completionTask(int $taskId)
    {
        $task = Task::findOne($taskId);
        $task->status = $this->isCompletion === self::STATUS_YES ? Task::STATUS_COMPLETED : Task::STATUS_FAILING;
        $task->save();

        $taskRespond = TaskRespond::find()->where("task_id = $taskId AND status = '" . TaskRespond::STATUS_ACCEPTED . "'")->one();
        $rewiew = new Review();
        $rewiew->text = $this->text;
        $rewiew->rating = $this->rating;
        $rewiew->task_id = $taskId;
        $rewiew->author_id = $task->author_id;
        $rewiew->executor_id = $taskRespond->user_id;
        $rewiew->save();
    }
}
