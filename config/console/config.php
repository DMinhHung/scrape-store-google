<?php

use yii\queue\LogBehavior;
use yii\redis\Connection;

$config = [
    'id' => 'console',
    'basePath' => dirname(__DIR__,2)."/src",
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        'migrate' => [
            'class' => yii\console\controllers\MigrateController::class,
            'migrationPath' => '@app/migrations/db',
            'migrationTable' => '{{%system_db_migration}}'
        ],
//        'rbac-migrate' => [
//            'class' => RbacMigrateController::className(),
//            'migrationPath' => '@app/migrations/rbac',
//            'migrationTable' => '{{%system_rbac_migration}}',
//            'templateFile' => '@app/rbac/views/migration.php'
//        ],
        'batch' => [
            'class' => 'schmunk42\giiant\commands\BatchController',
            'interactive' => false,
            'overwrite' => true,
            'skipTables' => ['system_db_migration','system_rbac_migration'],
            'modelNamespace' => 'app\models',
            'crudTidyOutput' => false,
            'useTranslatableBehavior' => true,
            'useTimestampBehavior' => true,
            'enableI18N' => false,
            'modelQueryNamespace' => 'app\common',
            'modelBaseClass' => yii\db\ActiveRecord::className(),
            'modelQueryBaseClass' => yii\db\ActiveQuery::className()
        ],
    ],

    'components' => [
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

    ],
    'bootstrap' => ['queue'],

];
if (YII_ENV_DEV) {
    //Config gii batch
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
    // Load Dumper
//    $config['bootstrap'][] = 'dumper';
//    $config['modules']['dumper'] = [
//        'class' => 'Guanguans\YiiVarDumper\Module.php',
//        //'host' => 'tcp://127.0.0.1:9913'
//    ];
}
return $config;