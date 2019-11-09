<?php

namespace app\Task;

use app\Action\Action;

class Task
{

    const ROLE_OWNER = 'owner'; // заказчик
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

    private $executorId;
    private $authorId;
    private $status;

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getRole($userId)
    {
        return [
                // (владелец) если переданный id совпадает с id создателя задания из таблицы
                $this->authorId => self::ROLE_OWNER,
                // (выбранный исполнитель) если переданный id совпадает с executorId у задания из таблицы
                $this->executorId => self::ROLE_SELECTED_EXECUTOR,
            ][$userId] ?? self::ROLE_EXECUTOR;
        // (исполнитель) в противном случае
    }

    public function getRoleList()
    {
        return [
            self::ROLE_OWNER,
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
