<?php
namespace frontend\assets;

use Yii;
use frontend\components\YandexMap\YandexMap;
use yii\web\AssetBundle;

class TaskViewAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [];

    public function __construct($config = [])
    {
        $this->js = [
            "https://api-maps.yandex.ru/2.1/?apikey=".Yii::$container->get('yandexMap')->apiKey."&lang=ru_RU",
            'js/main.js',
            'js/yandex-map.js',
            'js/messenger.js',
        ];

        parent::__construct($config);
    }
}
