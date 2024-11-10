<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;
use App\Models\Device;
use App\Models\User;
use App\Models\SensorData;

class NotificationController extends Controller
{

    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function sendWhatsAppMessage()
    {
        $to = '+5519989304364'; // Número de destino no formato internacional
        $message = 'Alerta: Uma queda foi detectada. Por favor, verifique.';

        $response = $this->whatsappService->sendWhatsAppMessage($to, $message);

        return response()->json([
            'status' => $response['status'],
            'message' => $response['message']
        ]);

    }

    public function checkForFallsAndNotify()
    {
        // Consulta para verificar eventos de queda na tabela `sensor_data`
        $fallEvents = SensorData::where('event_type', 'queda')
                                ->where('is_fall', 1)
                                ->get();

        foreach ($fallEvents as $event) {
            try {
                // Busca o dispositivo associado ao serial_number do evento
                $device = Device::where('serial_number', $event->serial_number)->first();

                if ($device) {
                    // Busca o usuário associado ao dispositivo
                    $user = User::find($device->user_id);

                    if ($user && $user->whatsapp_number) {
                        // Número de WhatsApp e mensagem de alerta
                        $to = '+55' . $user->whatsapp_number;
                        $message = 'Alerta: Uma queda foi detectada. Por favor, verifique.';

                        // Enviar mensagem de alerta via WhatsApp
                        $response = $this->whatsappService->sendWhatsAppMessage($to, $message);

                        Log::info("Notificação enviada para o WhatsApp de {$to}: " . $message);
                        return $response;
                    } else {
                        Log::warning("Usuário ou número de WhatsApp ausente para o dispositivo com serial number: {$event->serial_number}");
                    }
                } else {
                    Log::warning("Dispositivo não encontrado para o serial number: {$event->serial_number}");
                }
            } catch (\Exception $e) {
                Log::error("Erro ao enviar notificação de queda: " . $e->getMessage());
            }
        }
    }
}
