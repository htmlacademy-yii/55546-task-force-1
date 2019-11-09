<?php

function debug($data) {
    echo '<pre>' . print_r($data, true) . '</pre>';
}

require_once './vendor/autoload.php';

use app\Task\Task;
use app\Action\{Action, AvailableActions};

$task = new Task(['authorId' => 1, 'status' => Task::STATUS_NEW]);

AvailableActions::setData($task, 1);
debug(AvailableActions::getAvailableActions());
debug(AvailableActions::getNextStatus(Action::ACTION_CANCELED));
