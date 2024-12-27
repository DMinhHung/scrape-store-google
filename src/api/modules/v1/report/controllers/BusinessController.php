<?php
/**
 * Smart Digitech LLC (c) 2020, All right reverse
 * GMV_Milano Project
 */

namespace app\api\modules\v1\report\controllers;

use Yii;
use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\Exception;
use yii\rest\Controller;
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
        } catch (\Exception $e) {
            $response = new ResponseHelper(['status' => ApiConstant::STATUS_OK, 'message' => "Data retrieved fail", 'ok_status' => ApiConstant::STATUS_OK,]);
            return $response->build();
        }
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     * @throws \yii\base\Exception
     */
    public function actionIndex()
    {
        $latitude = Yii::$app->request->get('latitude');
        $longitude = Yii::$app->request->get('longitude');
        $zoom = Yii::$app->request->get('zoom');
        $distance = Yii::$app->request->get('distance');
        $token = Yii::$app->security->generateRandomString();

        $earth_radius = Business::EARTH_RADIUS;
        $points = [];

        for ($i = -1; $i <= 1; $i++) {
            for ($j = -1; $j <= 1; $j++) {
                $new_latitude = $latitude + ($i * $distance / $earth_radius) * (180 / pi());
                $new_longitude = $longitude + ($j * $distance / ($earth_radius * cos(deg2rad($latitude)))) * (180 / pi());
                $points[] = [
                    'latitude' => $new_latitude,
                    'longitude' => $new_longitude
                ];
            }
        }

        // Lấy thông tin cửa hàng từ cơ sở dữ liệu
        $business = Business::find()->where(['latitude' => $latitude, 'longitude' => $longitude])->one();

        $regionResults = [];
        $storePositions = [];

        $currentPoint = 0;

        // Xử lý dữ liệu các cửa hàng tại mỗi điểm
        foreach ($points as $point) {
            // Tính toán tiến trình xử lý
            $currentPoint++;
            $progress = min(100, rand($currentPoint * 10, ($currentPoint + 1) * 10));
            $message = "$progress%";

            // Xử lý dữ liệu cửa hàng tại mỗi điểm
            $q = "nail salon near me";
            $latitude = sprintf('%.15f', $point['latitude']);
            $longitude = sprintf('%.15f', $point['longitude']);
            $ll = "@$latitude,$longitude,$zoom";
            $apiKey = env('KEY_SCRAPER');
            $url = env('API_SCRAPER') . "&q=" . urlencode($q) . "&ll=" . urlencode($ll) . "&apiKey=" . $apiKey;

            $client = new Client();
            $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl($url)
                ->send();

            if ($response->isOk) {
                // Xử lý dữ liệu từ response
                if (isset($response->data['places']) && is_array($response->data['places'])) {
                    foreach ($response->data['places'] as $place) {
                        $placeId = $place['placeId'];

                        if (!isset($storePositions[$placeId])) {
                            $storePositions[$placeId] = [
                                'totalPosition' => 0, 'count' => 0, 'details' => $place,
                            ];
                        }

                        $storePositions[$placeId]['totalPosition'] += $place['position'];
                        $storePositions[$placeId]['count']++;

                        if ($placeId === $business->place_id) {
                            $regionResults[] = ['point' => $point, 'grid_point_rank' => $place['position']];
                        }
                    }
                }
            } else {
                // Nếu không có dữ liệu, thêm điểm vào kết quả
                $regionResults[] = ['point' => $point, 'grid_point_rank' => null];
            }

            // Log tiến trình xử lý
            Yii::info("Progress: $message", 'scrape');
        }

        // Tính toán vị trí trung bình của các cửa hàng
        $averageStorePositions = [];
        $limitedStorePositions = array_slice($storePositions, 0, 10, true);

        foreach ($limitedStorePositions as $data) {
            $averagePosition = $data['totalPosition'] / $data['count'];
            $averageStorePositions[] = [
                'store' => $data['details'],
                'average_position' => round($averagePosition, 2),
            ];
        }

        // Chuẩn bị dữ liệu trả về
        $payload = [
            'grid_point' => $regionResults,
            'average_positions' => $averageStorePositions,
            'token' => $token,
        ];

        // Trả về kết quả dưới dạng JSON
        return $this->asJson($payload);
    }
}