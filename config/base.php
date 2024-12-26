<?php

// Other Config Load

// Config for Monolog
$psrLogger = new \Monolog\Logger(env('APP_NAME', false), [
    new \Monolog\Handler\StreamHandler('php://stdout', YII_DEBUG ? \Monolog\Logger::DEBUG : \Monolog\Logger::INFO),
], [], new \DateTimeZone('UTC'));

if (!YII_DEBUG || env("SENTRY_ACTIVE", 0) == 1) {
    if (env("SENTRY_DSN", false)) {

        $client = \Sentry\ClientBuilder::create(['dsn' => env("SENTRY_DSN", false)])->getClient();
        $psrLogger->pushHandler(new \Sentry\Monolog\Handler(new \Sentry\State\Hub($client),\Monolog\Logger::ERROR));
    }
}

// Basic configuration, used in web and console applications
return [
    'id' => 'app',
    'language' => 'en',
    'basePath' => dirname(__DIR__).'/src',
    'vendorPath' => '@app/../vendor',
    'runtimePath' => '@app/../runtime',
       // Bootstrapped modules are loaded in every request
    'bootstrap' => [
        'log',
    ],
    'components' => [
        'log' => [
            'class' => yii\log\Dispatcher::class,
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'flushInterval' => 1,
            'targets' => [
                'psr3' => [
                    'class' => \samdark\log\PsrTarget::class,
                    'logger' => $psrLogger,

                    // It is optional parameter. The message levels that this target is interested in.
                    // The parameter can be an array.
                    'levels' => ['info', yii\log\Logger::LEVEL_WARNING, Psr\Log\LogLevel::CRITICAL, Psr\Log\LogLevel::ERROR],
                    // It is optional parameter. Default value is false. If you use Yii log buffering, you see buffer write time, and not real timestamp.
                    // If you want write real time to logs, you can set addTimestampToContext as true and use timestamp from log event context.
                    'addTimestampToContext' => true,
                    'logVars' => [],
                ],
            ],
        ],
    ],
];