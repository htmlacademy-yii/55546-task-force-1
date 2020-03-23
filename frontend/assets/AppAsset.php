<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 *
 * Class AppAsset
 *
 * @package frontend\assets
 */
class AppAsset extends AssetBundle
{
    /** @var string строка с адресом базового пути */
    public $basePath = '@webroot';
    /** @var string строка с адресом базового пути */
    public $baseUrl = '@web';
    /** @var array массив со списком стилей */
    public $css
        = [
            'css/site.css',
            'css/normalize.css',
            'css/style.css',
        ];
    /** @var array массив со списком скриптов */
    public $js
        = [
            'js/all.js',
        ];
    /** @var array массив со списком зависимых ассетов */
    public $depends
        = [
            'yii\web\YiiAsset',
        ];
}
