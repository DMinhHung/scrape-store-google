<?php
return [
    'class' => 'yii\db\Connection',
//    'dsn' => 'sqlite:@app/../database.sqlite',
    'dsn' => 'mysql:host=' . env('DB_HOST') . ';dbname=' . env('DB_NAME') . ';port=' . env('DB_PORT'),
    'username' => env('DB_USERNAME'),
    'password' => env('DB_PASSWORD'),
    'charset' => env("DB_CHARSET", "utf8"),

    // Performance Tuning
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 3600,
    'schemaCache' => 'cache',

    // Optional: Enable query caching
    'enableQueryCache' => true,
    'queryCacheDuration'=> 3600,
    'queryCache' => 'cache',
];
