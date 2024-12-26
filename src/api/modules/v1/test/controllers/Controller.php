<?php


namespace app\api\modules\v1\test\controllers;

class Controller extends \yii\base\Controller
{
    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }

    public function afterAction($action, $result)
    {
        return parent::afterAction($action, $result);
    }
}