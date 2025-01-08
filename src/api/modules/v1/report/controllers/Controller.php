<?php
/**
 * Smart Digitech LLC (c) 2020, All right reverse
 * GMV_Milano Project
 */

namespace app\api\modules\v1\report\controllers;


use app\api\modules\v1\report\behaviors\Auth;

/**'
 * @author  Hung Dao <hungdm@dtsmart.vn>
 * @package api\modules\v1\inventory\controllers
 * @version 1.0.0
 */
class Controller extends \yii\rest\Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => Auth::class,
        ];
        return $behaviors;
    }
}