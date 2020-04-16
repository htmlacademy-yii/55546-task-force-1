<?php

namespace src\TaskHelper;

use app\models\Message;
use app\models\RespondForm;
use app\models\Review;
use app\models\Task;
use app\models\TaskCompletionForm;
use app\models\TaskRespond;
use src\NotificationHelper\NotificationHelper;
use Yii;

/**
 * Класс для работы с заданиями
 *
 * Class TaskHelper
 *
 * @package src\TaskHelper
 */
class TaskHelper
{
    /**
     * Завершение задания
     *
     * @param Task               $task объект задания
     * @param TaskCompletionForm $model модель формы завершения задания
     */
    public static function completion(Task $task, TaskCompletionForm $model): void
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $executor = $task->executor;
            $isCompletion = $model->getIsCompletion();
            $executor->updateTaskCounter($isCompletion);
            $task->finishing($isCompletion);

            (new Review([
                'text' => $model->text,
                'rating' => $model->rating,
                'task_id' => $task->id,
                'author_id' => $task->author_id,
                'executor_id' => $task->executor_id,
            ]))->save();

            if ($executor->userNotifications->is_task_actions) {
                NotificationHelper::taskComplete($executor, $task);
            }
            $transaction->commit();
        } catch (\Exception $err) {
            $transaction->rollBack();
        }
    }

    /**
     * Отказ исполнителя от задания
     *
     * @param Task        $task объект задания
     * @param TaskRespond $respond объект отклика исполнителя
     *
     * @throws \Throwable
     */
    public static function refusal(
        Task $task,
        TaskRespond $respond
    ): void {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $respond->delete();
            $task->executor->updateTaskCounter(false);
            $task->actionRefusal();

            if ($task->author->userNotifications->is_task_actions) {
                NotificationHelper::taskDenial($task->author, $task);
            }
            $transaction->commit();
        } catch (\Exception $err) {
            $transaction->rollBack();
        }
    }

    /**
     * Добавление нового отклика к заданию
     *
     * @param Task        $task объект задания
     * @param RespondForm $model модель формы отклика к заданию
     */
    public static function respond(
        Task $task,
        RespondForm $model
    ) {
        (new TaskRespond([
            'user_id' => Yii::$app->user->identity->id,
            'task_id' => $task->id,
            'text' => $model->text,
            'price' => $model->price,
            'status' => TaskRespond::STATUS_NEW,
            'public_date' => date('Y-m-d h:i:s'),
        ]))->save();

        if ($task->author->userNotifications->is_new_review) {
            NotificationHelper::taskRespond($task->author, $task);
        }
    }

    /**
     * Принятие/отклонение отклика к заданию
     *
     * @param Task        $task объект задания
     * @param TaskRespond $respond объект отклика к заданию
     * @param string      $status статус соответствующий выбранному решению автора задания
     */
    public static function decision(
        Task $task,
        TaskRespond $respond,
        string $status
    ) {
        if ($respond->getIsAccepted($status)) {
            $task->setExecutor($respond->user_id);

            if ($task->executor->userNotifications->is_task_actions) {
                NotificationHelper::taskStart($task->executor, $task);
            }
        }
        $respond->setStatusAccepted($status);
    }

    /**
     * Добавление нового сообщения к заданию
     *
     * @param Task    $task объект задания
     * @param Message $message объект нового сообщения к заданию
     *
     * @return array|null массив с данными нового задания
     */
    public static function message(Task $task, Message $message): ?array
    {
        $newMessage = null;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $message->save();
            $user = $task->getIsAuthor(Yii::$app->user->identity->id) ?
                $task->executor : $task->author;

            if ($user->userNotifications->is_new_message) {
                NotificationHelper::taskMessage($user, $task);
            }
            $newMessage = [
                'message' => $message->message,
                'published_at' => $message->published_at,
                'is_mine' => $message->is_mine,
                'task_id' => $message->task_id,
            ];
            $transaction->commit();
        } catch (\Exception $err) {
            $transaction->rollBack();
        }

        return $newMessage;
    }
}
