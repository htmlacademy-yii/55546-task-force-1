<?php

declare(strict_types=1);
ini_set('display_errors', 'On');
error_reporting(E_ALL);

function debug($data) {
    echo '<pre>' . print_r($data, true) . '</pre>';
}

require_once './vendor/autoload.php';

use app\Task\Task;
use app\Action\{Action, AvailableActions};
use app\InvalidTaskStatusException\InvalidTaskStatusException;
use app\InvalidSqlGeneratorPathException\InvalidSqlGeneratorPathException;
use app\SqlAppGenerator\SqlAppGenerator;

try {
    $task = new Task(['authorId' => 1, 'executorId' => 2, 'status' => Task::STATUS_EXECUTION]);
    AvailableActions::setData($task, 3);
    debug(AvailableActions::getAvailableActions());
    debug(AvailableActions::getNextStatus(Action::ACTION_CANCELED));

    $csvFiles = array_map(function($fileName) {
        return "./data/$fileName";
    }, array_diff(scandir('./data'), ['.', '..']));

    SqlAppGenerator::createSqlCollection($csvFiles,'./sql');
} catch (InvalidTaskStatusException $err) {
    echo 'Возникла ошибка при формировании задачи: ' . $err->getMessage();
} catch (InvalidSqlGeneratorPathException $err) {
    echo 'Возникла ошибка при генерации SQL файлов и CSV: ' . $err->getMessage();
}





