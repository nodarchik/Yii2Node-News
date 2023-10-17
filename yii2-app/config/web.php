<?php

use app\clients\AuthApiClient;
use app\clients\UserApiClient;
use app\clients\NewsApiClient;
use app\services\UserService;
use Dotenv\Dotenv;

// Load environment variables from the .env file
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$params = require __DIR__ . '/params.php';

$config = [
    'id' => getenv('APP_ID'),
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => getenv('APP_DEFAULT_ROUTE'),
    'components' => [
        'authApiClient' => [
            'class' => AuthApiClient::class,
            'client' => function() {
                return new GuzzleHttp\Client([
                    'timeout'  => (float) getenv('AUTH_API_CLIENT_TIMEOUT'),
                ]);
            },
            'baseUrl' => getenv('AUTH_API_BASE_URL'),
        ],
        'userApiClient' => [
            'class' => UserApiClient::class,
            'client' => function() {
                return new GuzzleHttp\Client([
                    'timeout'  => (float) getenv('USER_API_CLIENT_TIMEOUT'),
                ]);
            },
            'baseUrl' => getenv('USER_API_BASE_URL'),
        ],
        'newsApiClient' => [
            'class' => NewsApiClient::class,
            'client' => function() {
                return new GuzzleHttp\Client([
                    'timeout'  => (float) getenv('NEWS_API_CLIENT_TIMEOUT'),
                ]);
            },
            'baseUrl' => getenv('NEWS_API_BASE_URL'),
        ],
        'request' => [
            'cookieValidationKey' => getenv('APP_COOKIE_VALIDATION_KEY'),
        ],
        'user' => [
            'identityClass' => getenv('USER_IDENTITY_CLASS'),
            'enableAutoLogin' => filter_var(getenv('USER_ENABLE_AUTO_LOGIN'), FILTER_VALIDATE_BOOLEAN),
        ],
        'errorHandler' => [
            'errorAction' => getenv('ERROR_HANDLER_ACTION'),
        ],
        'mailer' => [
            'class' => getenv('MAILER_CLASS'),
            'useFileTransport' => filter_var(getenv('MAILER_USE_FILE_TRANSPORT'), FILTER_VALIDATE_BOOLEAN),
        ],
        'log' => [
            'traceLevel' => (int) getenv('LOG_TRACE_LEVEL'),
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => filter_var(getenv('APP_ENABLE_PRETTY_URL'), FILTER_VALIDATE_BOOLEAN),
            'showScriptName' => filter_var(getenv('APP_SHOW_SCRIPT_NAME'), FILTER_VALIDATE_BOOLEAN),
            'rules' => [],
        ],
    ],
    'params' => $params,
];

if (filter_var(getenv('APP_DEBUG'), FILTER_VALIDATE_BOOLEAN)) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => getenv('DEBUG_MODULE_CLASS'),
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => getenv('GII_MODULE_CLASS'),
    ];
}

return $config;
