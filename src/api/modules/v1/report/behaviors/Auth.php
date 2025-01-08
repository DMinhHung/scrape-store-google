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
    public function attach($owner)
    {
        // Cài đặt behavior cho owner nếu cần thiết
    }

    public function loginByAccessToken($token)
    {
        if ($token === getenv("PUBLIC_API_KEY")) {
            return true;
        }
        return false;
    }
}