<?php
/**
 * Configuration file for the "yii asset" console command
 */

// In the console environment, some path aliases may not exist. Please define these
Yii::setAlias('@webroot', __DIR__.'/frontend/web');
Yii::setAlias('@web', __DIR__.'/');

return [
    // Adjust command/callback for JavaScript files compressing:
    'jsCompressor' => 'java -jar compiler.jar --js {from} --js_output_file {to}',
    // Adjust command/callback for CSS files compressing:
    'cssCompressor' => 'java -jar yuicompressor.jar --type css {from} -o {to}',
    // Whether to delete asset source after compression:
    'deleteSource' => false,
    // The list of asset bundles to compress:
    'bundles' => [
        'frontend\assets\AppAsset',
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset',
        'frontend\assets\LandingAsset',
        'yii\authclient\widgets\AuthChoiceStyleAsset',
        'yii\widgets\ActiveFormAsset',
        'frontend\assets\ProfileAsset',
        'frontend\assets\TaskCreateAsset',
        'frontend\assets\TaskViewAsset',
        'frontend\assets\YandexMapAsset',
    ],
    // Asset bundle for compression output:
    'targets' => [
        'common' => [
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/all-{hash}.js',
            'css' => 'css/all-{hash}.css',
            'depends' => [
                'frontend\assets\AppAsset',
                'yii\web\YiiAsset',
                'yii\web\JqueryAsset',
            ],
        ],
        'landing' => [
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/landing-{hash}.js',
            'css' => 'css/landing-{hash}.css',
            'depends' => [
                'frontend\assets\LandingAsset',
                'yii\authclient\widgets\AuthChoiceStyleAsset',
            ],
        ],
        'profile' => [
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/profile-{hash}.js',
            'css' => 'css/profile-{hash}.css',
            'depends' => [
                'frontend\assets\ProfileAsset',
            ],
        ],
        'taskCreate' => [
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/task-create-{hash}.js',
            'css' => 'css/task-create-{hash}.css',
            'depends' => [
                'frontend\assets\TaskCreateAsset',
            ],
        ],
        'taskView' => [
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/task-view-{hash}.js',
            'css' => 'css/task-view-{hash}.css',
            'depends' => [
                'frontend\assets\TaskViewAsset',
            ],
        ],
    ],
    // Asset manager configuration:
    'assetManager' => [
        'basePath' => '@webroot/asset',
        'baseUrl' => '@web/asset',
    ],
];
