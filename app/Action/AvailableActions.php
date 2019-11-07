<?php

namespace app\Action;

use app\Task\Task;

class AvailableActions
{

    const ACTION_STATUS_MAP
        = [
            Action::ACTION_EXECUTION => Task::STATUS_EXECUTION,
            Action::ACTION_COMPLETED => Task::STATUS_COMPLETED,
            Action::ACTION_DENIAL => Task::STATUS_FAILING,
            Action::ACTION_CANCELED => Task::STATUS_CANCELED,
        ];

    private static $task;

    public static function setTask($task)
    {
        self::$task = $task;
    }

    public static function getNextStatus($action)
    {
        return in_array($action, self::getAvailableActions())
            ? self::ACTION_STATUS_MAP[$action] : null;
    }

    public static function getAvailableActions()
    {
        $actions = [
            Task::ROLE_CLIENT => [],
            Task::ROLE_EXECUTOR => [Action::ACTION_RESPOND],
            Task::ROLE_SELECTED_EXECUTOR => [],
        ];

        if (self::$task->status === Task::STATUS_EXECUTION) {
            $actions[Task::ROLE_SELECTED_EXECUTOR][] = Action::ACTION_DENIAL;
            $actions[Task::ROLE_CLIENT][] = Action::ACTION_COMPLETED;
        }

        if (self::$task->status === Task::STATUS_NEW) {
            $actions[Task::ROLE_CLIENT][] = Action::ACTION_EXECUTION;
            $actions[Task::ROLE_CLIENT][] = Action::ACTION_CANCELED;
        }

        return $actions[self::$task->role];
    }

}
