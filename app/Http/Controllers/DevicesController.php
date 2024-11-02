<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use PhpMqtt\Client\Facades\MQTT;
use App\Services\MqttService;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
//use App\Models\PersonDevice;
use App\Models\User;

class DevicesController extends Controller
{

    public function __construct(Device $device)
    {

        $this->device = $device;
    }


    // public function create1(Request $request)
    // {
    //     $request->validate([
    //         'user_id' => 'required|exists:users,id', // Verifique se o usuário existe
    //         //'person_id' => 'required|exists:persons,id', // Verifique se a pessoa existe
    //         'name' => 'required|string',
    //         'whatsapp_number' =>'required|integer'
    //     ]);

    //     // Cria o dispositivo
    //     $device = Device::create(['name' => $request->name]);

    //     // Associa o usuário, pessoa e dispositivo na tabela intermediária
    //     PersonDevice::create([
    //         'user_id' => $request->user_id,
    //         //'person_id' => $request->person_id,
    //         'whatsapp_number' => $request->whatsapp_number,
    //         'device_id' => $device->id,
    //     ]);

    //     return response()->json(['message' => 'Device created and associated successfully.', 'device' => $device]);
    // }

    public function create(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string',
            'serial_number' => 'required|string'

        ]);

        $device = Device::create(['name' => $request->name,'user_id' => $request->user_id,'serial_number' => $request->serial_number]);

        return response()->json(['message' => 'Device created and associated successfully.', 'device' => $device]);
    }


    public function index()
    {

        return response()->json($this->device->all(), 200);
    }

    public function store(Request $request)
    {

        $device = device::create([
            'user_id' => $request->input('user_id'),
            'name' => $request->input('name'),
            'serial_number' =>$request->input('serial_number')
        ]);
        return $device;
    }

    public function show($id)
    {
        $device = $this->device->find($id);
        if ($device === null) {
            return response()->json(['erro' => 'Dispositivo não encontrado.'], 404);
        }
        return response()->json($device, 200);
    }

    public function update(Request $request, $id)
    {

        $device = $this->device->find($id);
        if ($device === null) {
            return response()->json(['erro' => 'não foi possivel atualizar, dispositivo não encontrado.'], 404);
        }

        $device->update($request->all());
        $device->save();

        return response()->json($device, 200);
    }

    public function destroy(Device $device)
    {
        $device->delete();
        return response()->json(['sucess' => true]);
    }

    public function showDeviceByUser($userId)
{
    $user = User::find($userId);

    if ($user === null) {
        return response()->json(['erro' => 'Usuário não encontrado.'], 404);
    }

    $device = $user->devices()->where('user_id', $userId)->first();

    if ($device === null) {
        return response()->json(['erro' => 'Dispositivo não encontrado para este usuário.'], 404);
    }

    return response()->json($device, 200);
}


    public function sendSerialNumber(Request $request)
{
    // Obtém o número de série do request
    $serialNumber = $request->input('serial_number');

    // Verifica se o número de série foi fornecido
    if (!$serialNumber) {
        return response()->json(['error' => 'Serial number is required'], 400);
    }

    // Define o tópico para o MQTT
    $topic = "device/" . $serialNumber . "/command";

    // Obtém as configurações do .env
    $mqttHost = env('MQTT_HOST', 'localhost');
    $mqttPort = env('MQTT_PORT', 1883);
    $mqttUsername = env('MQTT_USERNAME');
    $mqttPassword = env('MQTT_PASSWORD');
    $mqttClientId = env('MQTT_CLIENT_ID');

    // Configuração do cliente MQTT
    $mqtt = new MqttClient($mqttHost, $mqttPort, $mqttClientId);

    // Criar configurações de conexão
    $settings = (new ConnectionSettings())
        ->setUsername($mqttUsername)
        ->setPassword($mqttPassword)
        ->setKeepAliveInterval(60); // Define um keep-alive para evitar desconexões

    try {
        // Conecta ao broker MQTT
        $mqtt->connect($settings);

        // Publica a mensagem no broker
        $message = json_encode(['serial_number' => $serialNumber, 'command' => 'start']);
        $mqtt->publish($topic, $message);

        // Retorna sucesso
        return response()->json(['success' => 'Serial number sent to device', 'serial_number' => $serialNumber]);
    } catch (\Exception $e) {
        // Retorna erro em caso de falha
        return response()->json(['error' => 'Failed to send message: ' . $e->getMessage()], 500);
    } finally {
        // Desconecta do broker
        $mqtt->disconnect();
    }
}

}


