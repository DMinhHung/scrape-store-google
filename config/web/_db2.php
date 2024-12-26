<?php
return [
    'class' => 'yii\db\Connection',
//    'dsn' => 'sqlite:@app/../database.sqlite',
    'dsn' => 'mysql:host='. env('DB_2_HOST') .';dbname='. env('DB_2_NAME') . ';port=' . env('DB_2_PORT'),
    'username' => env('DB_2_USERNAME'),
    'password' => env('DB_2_PASSWORD'),
    'charset' => env("DB_CHARSET", "utf8"),
];
