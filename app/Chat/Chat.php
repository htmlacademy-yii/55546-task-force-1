<?php

namespace app\Chat;

class Chat
{
    private $id = null;
    private $user_id = null;
    private $task_id = null;

    private $messages = [];

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->user_id = $data['user_id'] ?? null;
        $this->task_id = $data['task_id'] ?? null;
    }

    public function getAllMessages()
    {
        return $this->messages = [];
    }

    public function addMessage()
    {
        $this->messages[] = null;
    }

}
