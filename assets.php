<?php

use yii\web\AssetBundle;
use frontend\assets\TaskCreateAsset;
use frontend\assets\TaskViewAsset;
use frontend\assets\YandexMapAsset;
use frontend\assets\AppAsset;
use yii\web\YiiAsset;
use yii\web\JqueryAsset;
use frontend\assets\LandingAsset;
use yii\authclient\widgets\AuthChoiceStyleAsset;
use yii\widgets\ActiveFormAsset;
use frontend\assets\ProfileAsset;
use frontend\widgets\assets\DropzoneAsset;

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
        AppAsset::class,
        YiiAsset::class,
        JqueryAsset::class,
        LandingAsset::class,
        AuthChoiceStyleAsset::class,
        ActiveFormAsset::class,
        ProfileAsset::class,
        DropzoneAsset::class,
        TaskCreateAsset::class,
        TaskViewAsset::class,
        YandexMapAsset::class,
    ],
    // Asset bundle for compression output:
    'targets' => [
        'common' => [
            'class' => AssetBundle::class,
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/all-{hash}.js',
            'css' => 'css/all-{hash}.css',
            'depends' => [
                AppAsset::class,
                YiiAsset::class,
                JqueryAsset::class,
                ActiveFormAsset::class
            ],
        ],
        'landing' => [
            'class' => AssetBundle::class,
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/landing-{hash}.js',
            'css' => 'css/landing-{hash}.css',
            'depends' => [
                LandingAsset::class,
                AuthChoiceStyleAsset::class,
            ],
        ],
        'profile' => [
            'class' => AssetBundle::class,
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/profile-{hash}.js',
            'css' => 'css/profile-{hash}.css',
            'depends' => [
                ProfileAsset::class,
                DropzoneAsset::class
            ],
        ],
        'taskCreate' => [
            'class' => AssetBundle::class,
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/task-create-{hash}.js',
            'css' => 'css/task-create-{hash}.css',
            'depends' => [
                TaskCreateAsset::class,
            ],
        ],
        'taskView' => [
            'class' => AssetBundle::class,
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/task-view-{hash}.js',
            'css' => 'css/task-view-{hash}.css',
            'depends' => [
                TaskViewAsset::class,
            ],
        ],
    ],
    // Asset manager configuration:
    'assetManager' => [
        'basePath' => '@webroot/asset',
        'baseUrl' => '@web/asset',
    ],
];
