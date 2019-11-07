<?php

function debug($data) {
    echo '<pre>' . print_r($data, true) . '</pre>';
}

require_once './vendor/autoload.php';

use app\Task\Task;
use app\Action\Action;
use app\Action\AvailableActions;

$task = new Task(['role' => Task::ROLE_CLIENT]);

AvailableActions::setTask($task);
$nextStatus = AvailableActions::getNextStatus(Action::ACTION_EXECUTION);
var_dump($nextStatus);
