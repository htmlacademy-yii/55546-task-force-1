<?php

namespace app\Task;

class Task
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

    const ACTION_COMPLETED = 'completed'; // выполнить
    const ACTION_DENIAL = 'denial'; // отказаться
    const ACTION_CANCEL = 'cancel'; // отменить
    const ACTION_RESPOND = 'respond'; // откликнуться

    const ACTION_STATUS_MAP
        = [
            self::ACTION_COMPLETED => self::STATUS_COMPLETED,
            self::ACTION_DENIAL => self::STATUS_FAILING,
            self::ACTION_CANCEL => self::STATUS_CANCELED,
        ];

    private $id;
    private $title;
    private $description;
    private $category;
    private $files;
    private $location;
    private $price;
    private $dateEnd;

    private $role;
    private $executorId;
    private $clientId;
    private $currentStatus;

    private $errors = [];

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            if ($key === 'status' || $key === 'role'
                || !property_exists($this, $key)
            ) {
                continue;
            }

            $this->$key = $value;
        }

        if (in_array($data['status'], $this->getStatusList())) {
            $this->currentStatus = $data['status'];
        } elseif (isset($this->dateEnd)
            && strtotime($this->dateEnd) < time()
        ) {
            $this->currentStatus = self::STATUS_EXPIRED;
        } else {
            $this->currentStatus = self::STATUS_NEW;
        }

        $this->role = in_array($data['role'], $this->getRoleList())
            ? $data['role'] : self::ROLE_EXECUTOR;
    }

    public function getRoleList()
    {
        return [
            self::ROLE_CLIENT,
            self::ROLE_EXECUTOR,
            self::ROLE_SELECTED_EXECUTOR,
        ];
    }

    public function getStatusList()
    {
        return [
            self::STATUS_NEW,
            self::STATUS_EXECUTION,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELED,
            self::STATUS_FAILING,
            self::STATUS_EXPIRED,
        ];
    }

    public function getActionsList()
    {
        return [
            self::ACTION_COMPLETED,
            self::ACTION_DENIAL,
            self::ACTION_CANCEL,
            self::ACTION_RESPOND,
        ];
    }

    public function getNextStatus($action)
    {
        return in_array($action, $this->getAvailableActions()) ?
            $this->currentStatus = self::ACTION_STATUS_MAP[$action] : null;
    }

    private function getAvailableActions()
    {
        $actions = [
            self::ROLE_CLIENT => [self::ACTION_COMPLETED],
            self::ROLE_EXECUTOR => [self::ACTION_RESPOND],
            self::ROLE_SELECTED_EXECUTOR => [self::ACTION_DENIAL],
        ];

        if ($this->currentStatus === self::STATUS_NEW) {
            $actions[self::ROLE_CLIENT][] = self::ACTION_CANCEL;
        }

        return $actions[$this->role];
    }
}
