<?php
namespace frontend\components\TaskforceUrlManager;

use Yii;
use yii\web\UrlManager;

class TaskforceUrlManager extends UrlManager
{
    public function parseRequest($request) {
        $url = Yii::$app->request->url;
        if ($pos = strpos($url, 'index.php?r=') == false) {
            return parent::parseRequest($request);
        }
//        Yii::trace('Pretty URL not enabled. Using default URL parsing logic.', __METHOD__);
        $route = $request->getQueryParam($this->routeParam, '');
        if (is_array($route)) {
            $route = '';
        }

        return [(string) $route, []];
    }
}
