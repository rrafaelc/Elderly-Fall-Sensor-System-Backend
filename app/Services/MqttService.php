<?php
namespace App\Services;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use App\Http\Controllers\MQTTController;

class MqttService
{
    protected $client;

    public function __construct()
    {
        $host = env('MQTT_HOST');
        $port = env('MQTT_PORT');
        $clientId = env('MQTT_CLIENT_ID');

        $connectionSettings = (new ConnectionSettings)
            ->setUsername(env('MQTT_USERNAME'))
            ->setPassword(env('MQTT_PASSWORD'))
            ->setKeepAliveInterval(60)
            ->setLastWillTopic(env('MQTT_TOPIC'))
            ->setLastWillMessage('client disconnect')
            ->setLastWillQualityOfService(1);

        $this->client = new MqttClient($host, $port, $clientId);
        $this->client->connect($connectionSettings, true);
    }

    public function listen()
    {

        $this->client->subscribe(env('MQTT_TOPIC'), function (string $topic, string $message) {
            MQTTController::processData($message);
        }, 0);

        // $this->client->subscribe(env('MQTT_TOPIC'), function (string $topic, string $message) {
        //     MQTTController::processDataMongo($message);
        // }, 0);

        $this->client->loop(true);
    }

}

