<?php

namespace app\Task;

use app\Action\AvailableActions;

class Task
{
    const ROLE_CLIENT = AvailableActions::ROLE_CLIENT;
    const ROLE_EXECUTOR = AvailableActions::ROLE_EXECUTOR;
    const ROLE_SELECTED_EXECUTOR = AvailableActions::ROLE_SELECTED_EXECUTOR;

    const STATUS_NEW = AvailableActions::STATUS_NEW;
    const STATUS_EXECUTION = AvailableActions::STATUS_EXECUTION;
    const STATUS_COMPLETED = AvailableActions::STATUS_COMPLETED;
    const STATUS_CANCELED = AvailableActions::STATUS_CANCELED;
    const STATUS_FAILING = AvailableActions::STATUS_FAILING;
    const STATUS_EXPIRED = AvailableActions::STATUS_EXPIRED;

    private $id;
    private $title;
    private $description;
    private $category;
    private $files;
    private $location;
    private $price;
    private $dateEnd;

    public $role;
    private $executorId;
    private $clientId;
    public $status;

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
            $this->status = $data['status'];
        } elseif (isset($this->dateEnd)
            && strtotime($this->dateEnd) < time()
        ) {
            $this->status = self::STATUS_EXPIRED;
        } else {
            $this->status = self::STATUS_NEW;
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

}
