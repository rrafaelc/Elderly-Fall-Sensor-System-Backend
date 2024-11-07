<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppService;

class NotificationController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function sendWhatsAppMessage()
    {
        $to = '+5519989285414'; // Número de destino no formato internacional
        $message = 'Alerta: Uma queda foi detectada. Por favor, verifique.';

        $this->whatsappService->sendMessage($to, $message);

        return response()->json(['status' => 'Mensagem enviada com sucesso']);
    }
}

