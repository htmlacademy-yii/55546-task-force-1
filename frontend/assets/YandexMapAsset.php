<?php

namespace frontend\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * Ассет для компонента работы с яндекс картой
 *
 * Class YandexMapAsset
 *
 * @package frontend\assets
 */
class YandexMapAsset extends AssetBundle
{
    /** @var string строка с адресом базового пути */
    public $basePath = '@webroot';
    /** @var string строка с адресом базового пути */
    public $baseUrl = '@web';
    /** @var array массив со списком скриптов */
    public $js = [];

    /**
     * YandexMapAsset constructor.
     *
     * @param array $config
     *
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function __construct($config = [])
    {
        $this->js = [
            'https://api-maps.yandex.ru/2.1/?apikey='
            .Yii::$container->get('yandexMap')->apiKey.'&lang=ru_RU',
        ];

        parent::__construct($config);
    }
}
