<?php

function debug($data) {
    echo '<pre>' . print_r($data, true) . '</pre>';
}

require_once './vendor/autoload.php';

use app\Task\Task;
use app\Action;

$task = new Task(['role' => Action\AvailableActions::ROLE_CLIENT]);
$availableAction = new Action\AvailableActions($task);

$nextStatus = $availableAction->getNextStatus(Action\AvailableActions::ACTION_EXECUTION);

var_dump($nextStatus);
