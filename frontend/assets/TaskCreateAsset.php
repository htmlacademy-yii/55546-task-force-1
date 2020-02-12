<?php
namespace frontend\assets;

use yii\web\AssetBundle;

class TaskCreateAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/dropzone.js',
        'js/task-create-init.js',
    ];
}
