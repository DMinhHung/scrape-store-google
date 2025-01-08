<?php
/**
 * Smart Digitech LLC (c) 2020, All right reverse
 * GMV_Milano Project
 */

namespace app\api\modules\v1\report\controllers;

use Yii;
use app\api\helper\response\ApiConstant;
use app\api\helper\response\ResponseHelper;
use app\api\modules\v1\report\models\Business;
use app\api\modules\v1\report\job\ScrapeStoreGoogleMapJob;

/**'
 * @author  Hung Dao <hungdm@dtsmart.vn>
 * @package api\modules\v1\inventory\controllers
 * @version 1.0.0
 */
class BusinessController extends Controller
{
    public function actionInfo()
    {
        $request = Yii::$app->request;
        if ($request->isGet) {
            $placeId = $request->getQueryParam('place_id');

            $business = Business::find()->where(['place_id' => $placeId])->one();
            if ($business) {
                $response = new ResponseHelper(['status' => ApiConstant::STATUS_OK, 'data' => $business, 'message' => 'businesses found', 'ok_status' => ApiConstant::STATUS_OK,]);
                return $response->build();
            }
        }
        $response = new ResponseHelper(['status' => ApiConstant::STATUS_FAIL, 'message' => "Only Get allowed!", "error" => ApiConstant::SC_INVALID_METHOD,]);
        return $response->build();
    }

    public function actionStore()
    {
        $request = Yii::$app->request;
        try {
            $latitude = $request->getQueryParam('latitude');
            $longitude = $request->getQueryParam('longitude');
            $zoom = $request->getQueryParam('zoom');
            $distance = $request->getQueryParam('distance');

            $token = Yii::$app->security->generateRandomString();

            // Push job to queue
            Yii::$app->queue->push(new ScrapeStoreGoogleMapJob([
                'latitude' => $latitude,
                'longitude' => $longitude,
                'zoom' => $zoom,
                'distance' => $distance,
                'token' => $token
            ]));

            $response = new ResponseHelper(['status' => ApiConstant::STATUS_OK, 'data' => $token, 'message' => "Data retrieved successfully", 'ok_status' => ApiConstant::STATUS_OK,]);
            return $response->build();
        } catch (\Exception) {
            $response = new ResponseHelper(['status' => ApiConstant::STATUS_FAIL, 'message' => "Data retrieved fail", 'ok_status' => ApiConstant::STATUS_FAIL,]);
            return $response->build();
        }
    }
}