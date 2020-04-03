<?php

namespace src\TaskforceUrlManager;

use yii\web\UrlManager;

/**
 * Класс для адаптации стандартного UrlManager к работе с разными типами адресов
 *
 * Class TaskforceUrlManager
 *
 * @package src\TaskforceUrlManager
 */
class TaskforceUrlManager extends UrlManager
{
    /**
     * Адаптация стандартного UrlManager для работы с разными типами адресов
     *
     * @param \yii\web\Request $request - объект запроса
     *
     * @return array|bool
     */
    public function parseRequest($request)
    {
        if (strpos($request->url, 'index.php?r=') !== false) {
            $this->enablePrettyUrl = true;
            $request->url = $request->getQueryParam($this->routeParam, '');
            unset($_GET[$this->routeParam]);
        }

        return parent::parseRequest($request);
    }
}
