<?php


namespace app\common\helper\response;

/**
 * class ResultHelper
 *
 * @author  Binh Nguyen <binhnt@dtsmart.vn>
 * @package app\common\helper\response
 * Date: 10/21/2022
 * Time: 11:24 PM
 * @version 1.0.0
 */
class ResultHelper
{
    /**
     * Function build
     *
     * @param $status
     * @param $data
     * @param $error
     * @param $message
     * @return array|null[]
     * @author Binh Nguyen <binhnt@dtsmart.vn>
     */
    public static function build($status = null, $data = null, $error = null, $message = null)
    {
        return [
            'status' => $status,
            'data' => $data,
            'error' => $error,
            'message' => $message
        ];
    }
}