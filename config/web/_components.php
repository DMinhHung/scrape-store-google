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
//    'db2' => require('_db2.php'),

    'redis' => [
        'class' => Connection::class,
        'hostname' => '103.150.124.149',
        'port' => 11379,
        'database' => 0,
        'retries' => 1,
    ],

    'log' => [
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['error', 'warning', 'info'],
                'logFile' => '@runtime/logs/app.log',
                'maxFileSize' => 1024 * 2,
                'maxLogFiles' => 5,
            ],
        ],
    ],

    'queue' => [
        'class' => \yii\queue\redis\Queue::class,
        'redis' => 'redis',
        'channel' => 'queue',
        'ttr' => 600,
        'attempts' => 3,
        'mutex' => [
            'class' => \yii\redis\Mutex::class,
        ],
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
