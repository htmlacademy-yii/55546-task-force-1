<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Ассет для главной страницы сайта
 *
 * Class LandingAsset
 *
 * @package frontend\assets
 */
class LandingAsset extends AssetBundle
{
    /** @var string строка с адресом базового пути */
    public $basePath = '@webroot';
    /** @var string строка с адресом базового пути */
    public $baseUrl = '@web';
    /** @var array массив со списком скриптов */
    public $js = ['js/main.js'];
}
