<?php

namespace app\Chat;

class Chat
{
    private $id;
    private $userId;
    private $taskId;

    private $messages = [];

    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function getAllMessages(): array
    {
        return $this->messages;
    }

    public function addMessage(array $data): void
    {
        $this->messages[] = $data;
    }
}
