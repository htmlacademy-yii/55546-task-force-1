<?php
namespace frontend\components\TaskforceUrlManager;

use Yii;
use yii\web\UrlManager;

class TaskforceUrlManager extends UrlManager
{
    public function parseRequest($request) {
        if (strpos($request->url, 'index.php?r=') !== false) {
            $this->enablePrettyUrl = true;
            $request->url = $request->getQueryParam($this->routeParam, '');
            unset($_GET[$this->routeParam]);
        }

        return parent::parseRequest($request);
    }
}
