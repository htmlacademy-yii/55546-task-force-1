<?php

namespace src\NotificationHelper;

use app\models\EventRibbon;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * Класс для создания уведомлений при совершении различных действий на сайте
 *
 * Class NotificationHelper
 *
 * @package src\NotificationHelper
 * */
class NotificationHelper
{
    /**
     * Создание нового уведомления для действия - отклик на задание.
     *
     * @param ActiveRecord $authorTask - объект создателя задачи
     * @param ActiveRecord $task       - объект задачи
     * */
    public static function taskRespond(
        ActiveRecord $authorTask,
        ActiveRecord $task
    ): void {
        self::send($authorTask, $task, [
            'type' => EventRibbon::TYPE_NEW_TASK_RESPOND,
            'messageEvent' => 'Новый отклик к заданию',
            'titleMail' => "Новый отклик к вашему заданию "
                .Html::encode($task->title),
            'messageMail' => "<p>Был оставлен новый отклик к вашему заданию "
                .Html::a(Html::encode($task->title),
                    "http://yii-taskforce/tasks/view/$task->id").".</p>",
        ]);
    }

    /**
     * Создание нового уведомления для действия - начало задания.
     *
     * @param ActiveRecord $executorTask - объект исполнителя задачи
     * @param ActiveRecord $task         - объект задачи
     * */
    public static function taskStart(
        ActiveRecord $executorTask,
        ActiveRecord $task
    ): void {
        self::send($executorTask, $task, [
            'type' => EventRibbon::TYPE_TASK_START,
            'messageEvent' => 'Вы выбраны исполнителем',
            'titleMail' => "Ваш отклик к заданию ".Html::encode($task->title)
                ." был принят",
            'messageMail' => "<p>Ваш отклик к заданию "
                .Html::a(Html::encode($task->title),
                    "http://yii-taskforce/tasks/view/$task->id")
                ." был принят.</p>",
        ]);
    }

    /**
     * Создание нового уведомления для действия - завершение задания.
     *
     * @param ActiveRecord $executorTask - объект исполнителя задачи
     * @param ActiveRecord $task         - объект задачи
     * */
    public static function taskComplete(
        ActiveRecord $executorTask,
        ActiveRecord $task
    ): void {
        self::send($executorTask, $task, [
            'type' => EventRibbon::TYPE_TASK_COMPLETE,
            'messageEvent' => 'Задание завершено',
            'titleMail' => "Задание ".Html::encode($task->title)." завершено",
            'messageMail' => "<p>Заказчик завершил взятое вами задание "
                .Html::a(Html::encode($task->title),
                    "http://yii-taskforce/tasks/view/$task->id").".</p>",
        ]);
    }

    /**
     * Создание нового уведомления для действия - отказ от задания.
     *
     * @param ActiveRecord $executorTask - объект исполнителя задачи
     * @param ActiveRecord $task         - объект задачи
     * */
    public static function taskDenial(
        ActiveRecord $executorTask,
        ActiveRecord $task
    ): void {
        self::send($executorTask, $task, [
            'type' => EventRibbon::TYPE_TASK_DENIAL,
            'messageEvent' => 'Исполнитель отказался от задания',
            'titleMail' => "Исполнитель отказался от задания "
                .Html::encode($task->title),
            'messageMail' => "<p>Исполнитель отказался от задания "
                .Html::a(Html::encode($task->title),
                    "http://yii-taskforce/tasks/view/$task->id").".</p>",
        ]);
    }

    /**
     * Создание нового уведомления для действия - новое сообщение в чате.
     *
     * @param ActiveRecord $user - объект пользователя в чате
     * @param ActiveRecord $task - объект задачи
     * */
    public static function taskMessage(
        ActiveRecord $user,
        ActiveRecord $task
    ): void {
        self::send($user, $task, [
            'type' => EventRibbon::TYPE_NEW_CHAT_MESSAGE,
            'messageEvent' => 'Новое сообщение',
            'titleMail' => "К заданию ".Html::encode($task->title)
                ." было оставлено новое сообщение",
            'messageMail' => "<p>К заданию ".Html::a(Html::encode($task->title),
                    "http://yii-taskforce/tasks/view/$task->id")
                ." было оставлено новое сообщение.</p>",
        ]);
    }

    /**
     * Создание нового уведомления для действия - новое сообщение в чате.
     *
     * @param ActiveRecord $user - объект пользователя для которого нужно создать уведомление
     * @param ActiveRecord $task - объект задачи с которой связано уведомление
     * @param array        $data - объект с даннми для конкретного уведомления
     * */
    private static function send(
        ActiveRecord $user,
        ActiveRecord $task,
        array $data
    ): void {
        (new EventRibbon([
            'user_id' => $user->id,
            'task_id' => $task->id,
            'type' => $data['type'],
            'message' => $data['messageEvent'],
        ]))->save();
        Yii::$app->mailer->compose()
            ->setTo($user->email)
            ->setFrom([Yii::$app->params['adminEmail'] => 'admin yii-taskforce'])
            ->setSubject($data['titleMail'])
            ->setHtmlBody($data['messageMail'])
            ->send();
    }
}
