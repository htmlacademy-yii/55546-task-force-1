<?php

use yii\caching\FileCache;
use src\YandexMap\YandexMap;

return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@src' => dirname(dirname(__DIR__)).'/src',
    ],
    'vendorPath' => dirname(dirname(__DIR__)).'/vendor',
    'components' => [
        'cache' => [
            'class' => FileCache::class,
        ],
    ],
    'container' => [
        'singletons' => [
            'yandexMap' => [
                'class' => YandexMap::class,
                'apiKey' => 'e666f398-c983-4bde-8f14-e3fec900592a',
            ],
        ],
    ],
];
