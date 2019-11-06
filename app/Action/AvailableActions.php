<?php

namespace app\Action;

class AvailableActions
{

    const ROLE_CLIENT = 'client'; // заказчик
    const ROLE_EXECUTOR = 'executor'; // исполнитель
    const ROLE_SELECTED_EXECUTOR = 'selected-executor'; // выбранный исполнитель

    const STATUS_NEW = 'new'; // новое
    const STATUS_EXECUTION = 'execution'; // выполняется
    const STATUS_COMPLETED = 'completed'; // завершено
    const STATUS_CANCELED = 'canceled'; // отменено
    const STATUS_FAILING = 'failing'; // провалено
    const STATUS_EXPIRED = 'expired'; // просрочено

    const ACTION_COMPLETED = 'action_completed'; // выполнить
    const ACTION_DENIAL = 'action_denial'; // отказаться
    const ACTION_EXECUTION = 'action_execution'; // на выполнении
    const ACTION_CANCELED = 'action_canceled'; // отменить
    const ACTION_RESPOND = 'action_respond'; // откликнуться

    const ACTION_STATUS_MAP
        = [
            self::ACTION_EXECUTION => self::STATUS_EXECUTION,
            self::ACTION_COMPLETED => self::STATUS_COMPLETED,
            self::ACTION_DENIAL => self::STATUS_FAILING,
            self::ACTION_CANCELED => self::STATUS_CANCELED,
        ];

    private $task;

    public function __construct($task)
    {
        $this->task = $task;
    }

    public function getNextStatus($action)
    {
        return in_array($action, $this->getAvailableActions())
            ? self::ACTION_STATUS_MAP[$action] : null;
    }

    private function getAvailableActions()
    {
        $actions = [
            self::ROLE_CLIENT => [],
            self::ROLE_EXECUTOR => [self::ACTION_RESPOND],
            self::ROLE_SELECTED_EXECUTOR => [],
        ];

        if ($this->task->status === self::STATUS_EXECUTION) {
            $actions[self::ROLE_SELECTED_EXECUTOR][] = self::ACTION_DENIAL;
            $actions[self::ROLE_CLIENT][] = self::ACTION_COMPLETED;
        }

        if ($this->task->status === self::STATUS_NEW) {
            $actions[self::ROLE_CLIENT][] = self::ACTION_EXECUTION;
            $actions[self::ROLE_CLIENT][] = self::ACTION_CANCELED;
        }

        return $actions[$this->task->role];
    }

}
