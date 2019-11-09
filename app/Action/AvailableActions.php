<?php

namespace app\Action;

use app\Task\Task;

class AvailableActions
{

    const ACTION_STATUS_MAP
        = [
            Action::ACTION_COMPLETED => Task::STATUS_COMPLETED,
            Action::ACTION_DENIAL => Task::STATUS_FAILING,
            Action::ACTION_CANCELED => Task::STATUS_CANCELED,
        ];

    private static $task;
    private static $userId;

    public static function setData($task, $userId)
    {
        self::$task = $task;
        self::$userId = $userId;
    }

    public static function getNextStatus($action)
    {
        return in_array($action, self::getAvailableActions())
            ? self::ACTION_STATUS_MAP[$action] : null;
    }

    public static function getAvailableActions()
    {
        $actions = [
            Task::ROLE_OWNER => [],
            Task::ROLE_EXECUTOR => [],
            Task::ROLE_SELECTED_EXECUTOR => [],
        ];

        $status = self::$task->getStatus();

        if ($status === Task::STATUS_EXECUTION) {
            $actions[Task::ROLE_SELECTED_EXECUTOR][] = Action::ACTION_DENIAL;
            $actions[Task::ROLE_OWNER][] = Action::ACTION_COMPLETED;
        }

        if ($status === Task::STATUS_NEW) {
            $actions[Task::ROLE_OWNER][] = Action::ACTION_CANCELED;
            $actions[Task::ROLE_EXECUTOR][] = Action::ACTION_RESPOND;
        }

        return $actions[self::$task->getRole(self::$userId)];
    }

}
