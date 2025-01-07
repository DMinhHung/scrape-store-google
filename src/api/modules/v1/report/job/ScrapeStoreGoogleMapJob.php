<?php
namespace app\api\modules\v1\report\job;

use Yii;
use yii\base\BaseObject;
use yii\httpclient\Client;
use yii\queue\JobInterface;
use yii\httpclient\Exception;
use yii\base\InvalidConfigException;
use app\api\components\mqtt\MQTTService;
use app\api\modules\v1\report\models\Business;

class ScrapeStoreGoogleMapJob extends BaseObject implements JobInterface
{
    public $latitude;
    public $longitude;
    public $zoom;
    public $distance;
    public $token;

    /**
     * @throws InvalidConfigException
     * @throws \yii\base\Exception
     */
    public function execute($queue): void
    {
        $mqttService = new MQTTService();

        if ($mqttService->connect()) {
            $points = $this->generateGridPoints();
            $business = $this->getBusinessInfo();

            $regionResults = [];
            $storePositions = [];

            $currentPoint = 0;
            $totalPoints = count($points);

            foreach ($points as $point) {
                $currentPoint++;
                $progress = min(100, ($currentPoint / $totalPoints) * 100);
                $message = "$progress%";

                $this->processStoreData($point, $regionResults, $storePositions, $business);

                $progressPayload = ['message' => $message, 'token' => $this->token];
                $mqttService->publish('local_report/business/data', json_encode($progressPayload));
            }

            $averageStorePositions = $this->calculateAveragePositions($storePositions);

            // Prepare payload for MQTT
            $mqttPayload = ['grid_point' => $regionResults, 'average_positions' => $averageStorePositions, 'token' => $this->token,];

            // Publish to MQTT
            $topic = 'local_report/business/data';
            $mqttService->publish($topic, message: json_encode($mqttPayload));
            $mqttService->disconnect();
        }
    }

    /**
     * @return array
     */
    private function generateGridPoints(): array
    {
        $earth_radius = Business::EARTH_RADIUS;
        $points = [];

        $latitude_conversion_factor = (180 / pi()) * ($this->distance / $earth_radius);
        $cos_latitude = cos(deg2rad($this->latitude));
        $longitude_conversion_factor = (180 / pi()) * ($this->distance / ($earth_radius * $cos_latitude));

        for ($i = -1; $i <= 1; $i++) {
            for ($j = -1; $j <= 1; $j++) {
                $new_latitude = $this->latitude + ($i * $latitude_conversion_factor);
                $new_longitude = $this->longitude + ($j * $longitude_conversion_factor);

                $points[] = [
                    'latitude' => $new_latitude,
                    'longitude' => $new_longitude
                ];
            }
        }

        return $points;
    }

    /**
     * @return array
     */
    private function getBusinessInfo(): array
    {
        return Business::find()->where(['latitude' => $this->latitude, 'longitude' => $this->longitude])->one();
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    private function processStoreData($point, &$regionResults, &$storePositions, $business): void
    {
        $latitude = sprintf('%.15f', $point['latitude']);
        $longitude = sprintf('%.15f', $point['longitude']);
        $ll = "@$latitude,$longitude,$this->zoom";
        $apiKey = env('KEY_SCRAPER');
        $url = env('API_SCRAPER') . "&q=" . urlencode(Business::DEFAUL_SEARCH) . "&ll=" . urlencode($ll) . "&apiKey=" . $apiKey;

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl($url)
            ->send();

        if ($response->isOk) {
            $foundPlace = false;

            if ($response->data['places']) {
                foreach ($response->data['places'] as $place) {
                    $placeId = $place['placeId'];

                    if (!isset($storePositions[$placeId])) {
                        $storePositions[$placeId] = ['totalPosition' => 0, 'count' => 0, 'details' => $place];
                    }

                    $storePositions[$placeId]['totalPosition'] += $place['position'];
                    $storePositions[$placeId]['count']++;

                    if ($placeId === $business->place_id) {
                        $regionResults[] = [
                            'point' => $point,
                            'grid_point_rank' => $place['position'],
                        ];
                        $foundPlace = true;
                    }
                }
                if (!$foundPlace) {
                    $regionResults[] = [
                        'point' => $point,
                        'grid_point_rank' => null,
                    ];
                }
            }
        }
    }

    /**
     * @param $storePositions
     * @return array
     */
    private function calculateAveragePositions($storePositions): array
    {
        return array_map(function($data) {
            $averagePosition = $data['totalPosition'] / $data['count'];
            return [
                'store_avg' => round($averagePosition, 2),
                'store_title' => $data['details']['title'],
                'store_rating' => $data['details']['rating'],
                'store_category' => $data['details']['type'],
                'store_address' => $data['details']['address'],
                'store_latitude' => $data['details']['latitude'],
                'store_longitude' => $data['details']['longitude'],
            ];
        }, array_slice($storePositions, 0, 10, true));
    }
}
