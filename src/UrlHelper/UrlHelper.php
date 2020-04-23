<?php

namespace src\UrlHelper;

use yii\helpers\Url;

/**
 * Класс для работы c часто используемыми ссылками сайта
 *
 * Class UrlHelper
 *
 * @package src\UrlHelper
 */
class UrlHelper
{
    /**
     * Метод для получение строки с ссылкой на страницу со списком заданий
     *
     * @return string
     */
    public static function getBaseTasksUrl(): string
    {
        return Url::to('/tasks');
    }

    /**
     * Метод для получения строки с ссылкой на страницу пользователя с указанным id
     *
     * @param int $userId идентификатор пользователя
     *
     * @return string строка с ссылкой на страницу указанного пользователя
     */
    public static function createUserUrl(int $userId): string
    {
        return Url::to(['users/view', 'id' => $userId]);
    }

    /**
     * Метод для получение строки с ссылкой на страницу задания с указанным id
     *
     * @param int $taskId идентификатор задания
     *
     * @return string строка с ссылкой на страницу указанного задания
     */
    public static function createTaskUrl(int $taskId): string
    {
        return Url::to(['tasks/view', 'id' => $taskId]);
    }

    /**
     * Метод для получение строки с ссылкой на страницу со списком заданий
     * отфильтрованных по указанной категории
     *
     * @param int $categoryId категория для фильтрации заданий
     *
     * @return string строка с ссылкой на страницу со списком отфильтрованных заданий
     */
    public static function createTaskUrlByCategory(int $categoryId): string
    {
        return Url::to("/tasks?filter[categories][]=$categoryId");
    }
}
