<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\SensorData;
use App\Models\Device;
use App\Models\User;

class MQTTController extends Controller
{

    public function index()
    {
        // Recupera todos os dados do SensorData
        $sensorData = SensorData::all();

        // Retorna os dados como JSON
        return response()->json($sensorData);
    }

    public function show($id)
    {
        // Recupera um registro específico pelo ID
        $sensorData = SensorData::find($id);

        if (!$sensorData) {
            return response()->json(['message' => 'Dados não encontrados'], 404);
        }

        return response()->json($sensorData);
    }

    public static function processData($message)
{
    // Decodificar a mensagem JSON
    $data = json_decode($message, true);

    // Loga os dados recebidos para análise
    Log::info('Dados recebidos no MQTT: ' . $message);

    // Verifique se a decodificação foi bem-sucedida e se os dados estão no formato esperado
    if (is_array($data) && isset($data['serial_number'], $data['event_type'], $data['is_fall'], $data['is_impact'], $data['acceleration'], $data['gyroscope'])) {
        try {
            SensorData::create([
                'serial_number' => $data['serial_number'],
                'event_type' => $data['event_type'],
                'is_fall' => $data['is_fall'],
                'is_impact' => $data['is_impact'],
                'ax' => $data['acceleration']['ax'],
                'ay' => $data['acceleration']['ay'],
                'az' => $data['acceleration']['az'],
                'gx' => $data['gyroscope']['gx'],
                'gy' => $data['gyroscope']['gy'],
                'gz' => $data['gyroscope']['gz']
            ]);

            // Loga mensagem de sucesso
            Log::info('Dados salvos no MySQL: ' . json_encode($data));

            self::validateAndNotify($data);
        } catch (\Exception $e) {
            Log::error('Erro ao salvar dados no MySQL: ' . $e->getMessage());
        }
    } else {
        // Loga um aviso com os dados inválidos recebidos para análise
        Log::warning('Dados inválidos recebidos: ' . $message);
    }
}

public static function validateAndNotify($data)
{
    // Verifique se o evento é uma queda e se o valor de `is_fall` é `true`
    if ($data['event_type'] === 'queda' && $data['is_fall'] === true) {
        try {
            // Busca o dispositivo usando o `serial_number`
            $device = Device::where('serial_number', $data['serial_number'])->first();

            // Verifica se o dispositivo foi encontrado
            if ($device) {
                // Obtém o `user_id` associado ao dispositivo
                $userId = $device->user_id;

                // Busca o usuário associado ao `user_id` e obtém o número do WhatsApp
                $user = User::find($userId);

                if ($user && $user->whatsapp_number) {
                    // Aqui você pode implementar o envio de notificação via WhatsApp
                    // Enviar notificação para o número de WhatsApp
                    Log::info('Notificação enviada para o WhatsApp: ' . $user->whatsapp_number);

                    // Exemplo de integração para enviar a notificação
                    // sendWhatsAppNotification($user->whatsapp_number, 'Alerta: Queda detectada!');
                } else {
                    Log::warning('Usuário não encontrado ou número de WhatsApp ausente para o serial number: ' . $data['serial_number']);
                }
            } else {
                Log::warning('Dispositivo não encontrado para o serial number: ' . $data['serial_number']);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao buscar usuário ou enviar notificação: ' . $e->getMessage());
        }
    }
 }
}
