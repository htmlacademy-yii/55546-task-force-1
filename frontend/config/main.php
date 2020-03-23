<?php

use frontend\modules\v1\src\RestMessagesUrlRule;

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
            'class' => 'frontend\modules\v1\Module',
        ],
    ],
    'components' => [
        'assetManager' => [
            'bundles' => (YII_ENV_PROD ? require __DIR__.'/assets-prod.php'
                : []),
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.mail.ru',
                'port' => 465,
                'encryption' => 'ssl',
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'language' => 'ru-RU',
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => 'localhost',
                'port' => 6379,
                'database' => 1,
            ],
        ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'vkontakte' => [
                    'class' => 'yii\authclient\clients\VKontakte',
                    'clientId' => '7338231',
                    'clientSecret' => 'a19mVwHIMAC2frErrefh',
                    'scope' => 'email',
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => [
                'name' => '_identity-frontend',
                'httpOnly' => true,
            ],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'class' => 'frontend\src\TaskforceUrlManager\TaskforceUrlManager',
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
                    'class' => RestMessagesUrlRule::class,
                    'pattern' => '/(?P<version>v\d+)\/messages\/(?P<task_id>\d+)$/',
                    'routes' => [
                        'GET' => '<version>/message',
                        'POST' => '<version>/message/create',
                    ],
                ],
                [
                    'class' => RestMessagesUrlRule::class,
                    'pattern' => '/api\/messages\/(?P<task_id>\d+)$/',
                    'routes' => [
                        'GET' => 'v1/message',
                        'POST' => 'v1/message/create',
                    ],
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/message'],
            ],
        ],
    ],
    'container' => [
        'definitions' => [
            'frontend\controllers\SettingsController' => [
                'avatarsPath' => 'users-files/avatars',
                'photosPath' => 'users-files/works',
            ],
            'frontend\controllers\TasksController' => [
                'tasksPath' => 'users-files/tasks',
            ],
        ],
    ],
    'params' => $params,
];
