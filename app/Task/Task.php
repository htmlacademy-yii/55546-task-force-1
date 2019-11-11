<?php

namespace app\Task;

use app\Action\Action;
use app\TaskException\TaskException;

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

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        if (!in_array($this->status, $this->getStatusList())) {
            throw new TaskException('Переданный статус для задания недопустим');
        }
    }

    public function getStatusList(): array
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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getRole(int $userId): string
    {
        return [
                $this->authorId => self::ROLE_OWNER,
                $this->executorId => self::ROLE_EXECUTOR,
            ][$userId] ?? '';
    }
}
