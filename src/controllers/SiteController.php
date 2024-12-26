<?php

namespace app\controllers;
use app\models\RequestModel;
use app\models\User;
use Yii;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;

class SiteController
{

    public function actionVersion()
    {
        return env("APP_VERSION")?getenv("APP_VERSION"):"unknown";
    }
    public function actionError()
    {
        // Handling All Error Exception;
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            return $exception;
        }
    }

    /**
     * Renders the start page.
     *
     * @return string
     */
    public function actionIndex()
    {
        return ":)))))";
    }

}