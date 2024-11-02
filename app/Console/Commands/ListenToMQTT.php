<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use App\Http\Controllers\MQTTController;

class ListenToMQTT extends Command
{
    protected $signature = 'mqtt:listen';
    protected $description = 'Listen to MQTT messages';

    public function handle()
{
    // Obter as configurações do .env
    $mqttHost = env('MQTT_HOST', 'localhost');
    $mqttPort = env('MQTT_PORT', 1883);
    $mqttUsername = env('MQTT_USERNAME');
    $mqttPassword = env('MQTT_PASSWORD');
    $mqttClientId = env('MQTT_CLIENT_ID');
    $mqttTopic = env('MQTT_TOPIC');

    // Configuração do cliente MQTT
    $mqtt = new MqttClient($mqttHost, $mqttPort, $mqttClientId);

    // Criar configurações de conexão
    $settings = (new ConnectionSettings())
        ->setUsername($mqttUsername)
        ->setPassword($mqttPassword)
        ->setKeepAliveInterval(60); // Defina um keep-alive para evitar desconexões

    while (true) {
        try {
            $mqtt->connect($settings);
            $this->info("Conexão bem-sucedida ao broker MQTT!");

            // Assinar o tópico e processar mensagens recebidas
            $mqtt->subscribe($mqttTopic, function ($topic, $message) {
                $this->info("Mensagem recebida em {$topic}: {$message}");
                MQTTController::processData($message);
            });
            // $mqtt->subscribe('taisbuenovidotto@gmail.com/1', function ($topic, $message) {
            //     $this->info("Mensagem recebida em {$topic}: {$message}");
            //     MQTTController::processData($message);
            // });


            // Manter o script em execução
            while (true) {
                $mqtt->loop(); // Ouvir as mensagens
                usleep(100000); // Pausa para evitar sobrecarga no loop
            }
        } catch (\PhpMqtt\Client\Exceptions\ConnectingToBrokerFailedException $e) {
            $this->error('Falha ao conectar ao broker: ' . $e->getMessage());
        } catch (\PhpMqtt\Client\Exceptions\DataTransferException $e) {
            $this->error('Erro ao transferir dados: ' . $e->getMessage());
            $this->info("Tentando reconectar ao broker...");
            sleep(5); // Aguarde antes de tentar reconectar
        }
    }
}


}
