<?php

namespace src\TaskHelper;

use app\models\Task;
use yii\validators\RangeValidator;

/**
 * Вспомогательный класс для работы с заданиями
 *
 * Class ActionTaskHelper
 *
 * @package src\ActionTaskHelper
 */
class TaskHelper
{
    /**
     * Получение название имени файла без лишнего текста
     *
     * @param string $fileName строка с адресом файла
     *
     * @return string строка с именем файла
     */
    public static function getTaskFileName(string $fileName): string
    {
        $arr = explode('/', $fileName);

        return end($arr);
    }

    /**
     * Получение статусов заданий которые должны быть видны на странице данной категории
     *
     * @param string $category строка с категорией
     *
     * @return array|null
     */
    public static function getStatusByCategoryTask(string $category): ?array
    {
        if (!empty($category)
            && !(new RangeValidator(['range' => Task::getStatusList()]))->validate($category)
        ) {
            return null;
        }

        $status = Task::getStatusList();
        if (!empty($category)) {
            $status = ($category === Task::STATUS_CANCELED
                || $category === Task::STATUS_FAILING) ?
                [Task::STATUS_CANCELED, Task::STATUS_FAILING] : [$category];
        }

        return $status;
    }
}
