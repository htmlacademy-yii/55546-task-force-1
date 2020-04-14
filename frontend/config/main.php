<?php

use yii\log\FileTarget;
use yii\web\JsonParser;
use yii\swiftmailer\Mailer;
use yii\rest\UrlRule;
use frontend\controllers\TasksController;
use frontend\controllers\SettingsController;
use common\models\User;
use yii\authclient\clients\VKontakte;
use yii\authclient\Collection;
use yii\redis\Cache;
use yii\i18n\Formatter;
use frontend\modules\v1\Module;
use yii\web\UrlManager;

$params = array_merge(
    require __DIR__.'/../../common/config/params.php',
    require __DIR__.'/../../common/config/params-local.php',
    require __DIR__.'/params.php',
    require __DIR__.'/params-local.php'
);

return [
    'timeZone' => 'UTC',
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'v1' => [
            'class' => Module::class,
        ],
    ],
    'components' => [
        'assetManager' => [
            'bundles' => file_exists(__DIR__.'/assets-prod.php')
                ? require __DIR__.'/assets-prod.php' : [],
        ],
        'mailer' => [
            'class' => Mailer::class,
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.mail.ru',
                'port' => 465,
                'encryption' => 'ssl',
            ],
        ],
        'formatter' => [
            'class' => Formatter::class,
            'language' => 'ru-RU',
        ],
        'cache' => [
            'class' => Cache::class,
            'redis' => [
                'hostname' => 'localhost',
                'port' => 6379,
                'database' => 1,
            ],
        ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'parsers' => [
                'application/json' => JsonParser::class,
            ],
        ],
        'authClientCollection' => [
            'class' => Collection::class,
            'clients' => [
                'vkontakte' => [
                    'class' => VKontakte::class,
                    'clientId' => '7338231',
                    'clientSecret' => 'a19mVwHIMAC2frErrefh',
                    'scope' => 'email',
                ],
            ],
        ],
        'user' => [
            'identityClass' => User::class,
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_identity-frontend',
                'httpOnly' => true,
            ],
        ],
        'session' => [
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'class' => UrlManager::class,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'tasks/view/<id>' => 'tasks/view',
                'tasks/completion/<taskId>' => 'tasks/completion',
                'tasks/respond/<taskId>' => 'tasks/respond',
                'tasks/refusal/<taskId>' => 'tasks/refusal',
                'tasks/cancel/<taskId>' => 'tasks/cancel',
                'tasks/decision/<respondId>/<status>' => 'tasks/decision',
                'users/view/<id>' => 'users/view',
                [
                    'class' => UrlRule::class,
                    'controller' => 'v1/message',
                    'extraPatterns' => [
                        'GET {id}' => 'index',
                        'POST {id}' => 'create',
                    ],
                ],
            ],
        ],
    ],
    'container' => [
        'definitions' => [
            SettingsController::class => [
                'avatarsPath' => 'users-files/avatars',
                'photosPath' => 'users-files/works',
            ],
            TasksController::class => [
                'tasksPath' => 'users-files/tasks',
            ],
        ],
    ],
    'params' => $params,
];
