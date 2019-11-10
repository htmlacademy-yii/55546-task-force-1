<?php

namespace app\Task;

use app\Action\Action;

class Task
{
    const ROLE_OWNER = 'owner'; // заказчик
    const ROLE_EXECUTOR = 'executor'; // исполнитель

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
            $this->authorId => self::ROLE_OWNER,
            $this->executorId => self::ROLE_EXECUTOR,
        ][$userId];
    }
}
