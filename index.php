<?php

function debug($data) {
    echo '<pre>' . print_r($data, true) . '</pre>';
}

require_once './vendor/autoload.php';

use app\Task\Task;

$task = new Task(['role' => Task::ROLE_SELECTED_EXECUTOR]);

var_dump($task->getNextStatus(Task::ACTION_DENIAL));
