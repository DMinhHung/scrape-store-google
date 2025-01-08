<?php

namespace app\api\components\mqtt;

use Yii;
use Bluerhinos\phpMQTT;
class MQTTService
{
    private $mqttClient;
    private $host;
    private $port;
    private $username;
    private $password;
    private $clientId;


    /**
     * MQTTService constructor.
     */
    public function __construct()
    {
        $this->host = env('MQTT_HOST_SERVER');
        $this->port = env("MQTT_PORT_SERVER");
        $this->username = env('MQTT_USERNAME_SERVER');
        $this->password = env('MQTT_PASSWORD_SERVER');
        $this->clientId = 'gmv-report' . rand(1000, 9999);

// Initialize the MQTT Client
        $this->mqttClient = new phpMQTT($this->host, $this->port, $this->clientId);
    }

    /**
     * Connect to the MQTT broker
     */
    public function connect()
    {
        try {
            if ($this->mqttClient->connect(true, null, $this->username, $this->password)) {
                Yii::info("Connected to MQTT broker at {$this->host}:{$this->port}");
                return true;
            } else {
                Yii::error("Could not connect to MQTT broker");
                return false;
            }
        } catch (\Exception $e) {
            Yii::error("Failed to connect to MQTT broker: " . $e->getMessage());
            return false;
        }
    }


    /**
     * Disconnect from the MQTT broker
     */
    public function disconnect()
    {
        try {
            $this->mqttClient->close();
            Yii::info("Disconnected from MQTT broker");
        } catch (\Exception $e) {
            Yii::error("Failed to disconnect from MQTT broker: " . $e->getMessage());
        }
    }

    /**
     * Publish a message to a specific MQTT topic
     *
     * @param string $topic
     * @param string $message
     */
    public function publish(string $topic, string $message)
    {
        try {
            $this->mqttClient->publish($topic, $message, 0);
            Yii::info("Published topic: {$topic}");
        } catch (\Exception $e) {
            Yii::error("Failed to publish to MQTT broker: " . $e->getMessage());
        }
    }

//    /**
//     * Publish the user's online/offline status
//     *
//     * @param string $status
//     */
//    public function publishUserStatus(string $status): void
//    {
//        $topic = 'devices/online-status/devices' . Yii::$app->user->id;
//        $this->publish($topic, $status);
//    }
}