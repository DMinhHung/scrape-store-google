<?php
/**
 * Smart Digitech LTD (c) 2020, All right reverse
 * Fast Boy Marketing Go Checkin 2020 Project
 */

namespace app\api\modules\v1\report\models;

use app\common\db\Business as BaseBusiness;

/**'
 * @author  Hung Dao <hungdm@dtsmart.vn>
 * @package api\modules\v1\inventory\models
 * @version 1.0.0
 */
class Business extends BaseBusiness
{
    const DEFAULT_DISTANCE = 1;
    const EARTH_RADIUS = 6371;
}
