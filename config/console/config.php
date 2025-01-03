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
//        'db2' => require('_db2.php'),

        'redis' => [
            'class' => Connection::class,
            'hostname' => '103.150.124.149',
            'port' => 11379,
            'database' => 0,
            'retries' => 1,
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