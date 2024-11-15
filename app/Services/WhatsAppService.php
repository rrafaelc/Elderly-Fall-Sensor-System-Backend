<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;
use Twilio\Exceptions\RestException;


class WhatsAppService
{
    protected $twilio;


    public function __construct()
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');

        if (empty($sid) || empty($token)) {
            throw new \Exception('Twilio SID ou Token de Autenticação não configurados');
        }

        $this->twilio = new Client($sid, $token);
    }
    public function sendWhatsAppMessage($to, $message)
    {
        try {
            $from = "whatsapp:" . env('TWILIO_WHATSAPP_NUMBER');
            $to = "whatsapp:" . $to;

            $messageResponse = $this->twilio->messages->create($to, [
                'from' => $from,
                'body' => $message,
            ]);

            if ($messageResponse->status === 'queued' || $messageResponse->status === 'sent' || $messageResponse->status === 'delivered') {
                Log::info("Mensagem enviada com sucesso!");
                Log::info("Para: {$to}");
            } else {
                Log::warning("Mensagem não enviada com sucesso.");
                Log::warning("Status: {$messageResponse->status}");
            }

            return [
                'status' => 'success',
                'message' => 'Mensagem enviada com sucesso!',

            ];
        } catch (RestException $e) {
            return [
                'status' => 'error',
                'message' => 'Erro ao enviar mensagem: ' . $e->getMessage(),
            ];
        }
    }
}
