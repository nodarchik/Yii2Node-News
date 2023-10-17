<?php

use app\clients\AuthApiClient;
use app\clients\UserApiClient;
use app\clients\NewsApiClient;
use app\services\UserService;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => 'site/index',
    'components' => [
        'authApiClient' => [
            'class' => AuthApiClient::class,
            'baseUrl' => 'http://localhost:3001/api',
        ],
        'userApiClient' => [
            'class' => UserApiClient::class,
            'baseUrl' => 'http://localhost:3002/api',
        ],
        'newsApiClient' => [
            'class' => NewsApiClient::class,
            'baseUrl' => 'http://localhost:3002/api',
        ],
        'request' => [
            'cookieValidationKey' => 'your-cookie-validation-key',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [],
        ],
        'container' => [
            'definitions' => [
                UserService::class => function ($container) {
                    $userApiClient = $container->get(UserApiClient::class);
                    return new UserService($userApiClient);
                },
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;