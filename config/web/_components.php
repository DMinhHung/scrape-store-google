<?php

use yii\redis\Connection;

$routeRules = require('_routes.php');
$components = [
    'errorHandler' => [
        'errorAction' => 'site/error',
    ],
    'user' => [
        'identityClass' => 'app\models\User',
        'enableAutoLogin' => true,
    ],
    'cache' => require("_cache.php"),
    'request' => [
        'parsers' => [
            'application/json' => 'yii\web\JsonParser',
        ],
        'enableCsrfCookie' => false,
        'enableCookieValidation' => false,
    ],
    // using DB
    'db' => require('_db.php'),

    'log' => [
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['error', 'warning'],
                'logFile' => '@runtime/logs/queue.log',
            ],
        ],
    ],

    'redis' => [
        'class' => Connection::class,
        'hostname' => env('REDIS_HOST'),
        'port' => env('REDIS_PORT'),
        'database' => 0,
        'retries' => 1,
    ],

    'mutex' => [
        'class' => \yii\redis\Mutex::class,
        'redis' => 'redis',
    ],

    'queue' => [
        'class' => \yii\queue\redis\Queue::class,
        'redis' => 'redis',
        'channel' => 'queue',
        'as log' => \yii\queue\LogBehavior::class,
    ],

    'urlManager' => [
        'enablePrettyUrl' => true,
        'showScriptName' => false,
        'enableStrictParsing' => false,
        // Comment Below if you only using UrlManager.
        'rules' => $routeRules['rules'],
    ],
    'response' => [
        'class' => 'yii\web\Response',
        'format' => \yii\web\Response::FORMAT_JSON,
    ],
];

return $components;
