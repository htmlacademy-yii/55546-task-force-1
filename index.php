<?php

function debug($data) {
    echo '<pre>' . print_r($data, true) . '</pre>';
}

require_once './vendor/autoload.php';

use app\Task\Task;
use app\Action\{Action, AvailableActions};
use app\InvalidTaskStatusException\InvalidTaskStatusException;

try {
    $task = new Task(['authorId' => 1, 'executorId' => 2, 'status' => Task::STATUS_EXECUTION]);
    AvailableActions::setData($task, 3);
    debug(AvailableActions::getAvailableActions());
    debug(AvailableActions::getNextStatus(Action::ACTION_CANCELED));
} catch (InvalidTaskStatusException $err) {
    echo 'Возникла ошибка при формировании задачи: ' . $err->getMessage();
}
