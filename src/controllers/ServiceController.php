<?php

namespace app\controllers;

use yii\rest\Controller;

class ServiceController extends Controller
{

    public function actionVersion()
    {
        return env("APP_VERSION")??"unknown";
    }

    public function actionAlive()
    {
        return 1;
    }
}