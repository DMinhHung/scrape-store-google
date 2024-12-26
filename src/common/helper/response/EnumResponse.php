<?php
/**
 * Created by PhpStorm.
 * User: UX501
 * Date: 4/9/2017
 * Time: 1:14 PM
 */

namespace app\common\helper\response;


use yii\base\BaseObject;

/**
 * class EnumResponse
 *
 * @author  Binh Nguyen <binhnt@dtsmart.vn>
 * @package app\common\helper\response
 * Date: 10/21/2022
 * Time: 11:21 PM
 * @version 1.0.0
 */
class EnumResponse extends BaseObject
{
    const STATUS_OK = 'OK';
    const STATUS_FAIL = 'FAIL';
    const STATUS_INVALID_METHOD = "INVALID_METHOD";
    const STATUS_MISSING_PARAMS = "MISSING_PARAMS";
}