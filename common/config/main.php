<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)).'/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
    'container' => [
        'singletons' => [
            'yandexMap' => [
                'class' => 'frontend\src\YandexMap\YandexMap',
                'apiKey' => 'e666f398-c983-4bde-8f14-e3fec900592a',
            ],
        ],
    ],
];
