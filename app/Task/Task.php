<?php

namespace app\Task;

use app\Action\Action;

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
