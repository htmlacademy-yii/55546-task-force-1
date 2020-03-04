<?php
use frontend\modules\api\components\RestMessagesUrlRule;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'api' => [
            'class' => 'frontend\modules\api\Module'
        ]
    ],
    'components' => [
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
            ]
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
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
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
            'class' => 'frontend\components\TaskforceUrlManager\TaskforceUrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'tasks/view/<id>' => 'tasks/view',
                'tasks/decision/<status>/<id>/<taskId>' => 'tasks/decision',
                [
                    'class' => RestMessagesUrlRule::class,
                    'pattern' => '/api\/messages\/(?P<task_id>\d+)$/',
                    'routes' => [
                        'GET' => 'api/message',
                        'POST' => 'api/message/create',
                    ]
                ],
                ['class' => 'yii\rest\UrlRule', 'controller' => 'api/message'],
            ],
        ],
    ],
    'container' => [
        'definitions' => [
            'frontend\controllers\ProfileController' => [
                'avatarsPath' => 'users-files/avatars'
            ]
        ],
        'singletons' => [
            'yandexMap' => [
                'class' => 'frontend\components\YandexMap\YandexMap',
                'apiKey' => 'e666f398-c983-4bde-8f14-e3fec900592a',
            ]
        ]
    ],
    'params' => $params,
];
