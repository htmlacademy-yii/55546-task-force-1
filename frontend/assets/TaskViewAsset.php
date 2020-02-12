<?php
namespace frontend\assets;

use frontend\components\YandexMap\YandexMap;
use yii\web\AssetBundle;

class TaskViewAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        "https://api-maps.yandex.ru/2.1/?apikey=".YandexMap::API_KEY."&lang=ru_RU",
        'js/main.js',
        'js/yandex-map.js',
    ];
}
