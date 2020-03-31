<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Ассет для страницы cоздания заданий
 *
 * Class TaskCreateAsset
 *
 * @package frontend\assets
 */
class TaskCreateAsset extends AssetBundle
{
    /** @var string строка с адресом базового пути */
    public $basePath = '@webroot';
    /** @var string строка с адресом базового пути */
    public $baseUrl = '@web';
    /** @var array массив со списком скриптов */
    public $js
        = [
            'js/dropzone.js',
            'js/task-create-init.js',
        ];
}
