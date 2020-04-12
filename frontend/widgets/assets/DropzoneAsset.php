<?php

namespace frontend\widgets\assets;

use yii\web\AssetBundle;

/**
 * Ассет для виджета DropzoneWidget
 *
 * Class DropzoneAsset
 *
 * @package frontend\widgets\assets
 */
class DropzoneAsset extends AssetBundle
{
    /** @var string строка с адресом базового пути */
    public $basePath = '@webroot';
    /** @var string строка с адресом базового пути */
    public $baseUrl = '@web';
    /** @var array массив со списком скриптов */
    public $js = ['js/dropzone.js'];
}
