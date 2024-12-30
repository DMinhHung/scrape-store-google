<?php

use yii\queue\LogBehavior;

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
        'db2' => require('_db2.php'),

        'queue' => [
            'class' => \yii\queue\db\Queue::class,
            'db' => 'db2',
            'tableName' => '{{%queue}}',
            'channel' => 'default',
            'mutex' => \yii\mutex\MysqlMutex::class,
            'serializer' => \yii\queue\serializers\JsonSerializer::class,
            'as log' => LogBehavior::class
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