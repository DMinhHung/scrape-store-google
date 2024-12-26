<?php
/**
 * Smart Digitech LLC (c) 2020, All right reverse
 * GMV_Milano Project
 */
namespace app\api\modules\v1\report\behaviors;

use yii\base\Model;

/**'
 * @author  Hung Dao <hungdm@dtsmart.vn>
 * @package api\modules\v1\inventory\controllers
 * @version 1.0.0
 */
class Auth extends Model
{
    public function loginByAccessToken($token, $type = null)
    {
        $publicApiKey = env("PUBLIC_API_KEY");
        if ($token === $publicApiKey) {
            return true;
        }
        return null;
    }
}