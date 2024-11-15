<?php

namespace App\Services;

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
                print_r([
                    'message' => 'Mensagem enviada com sucesso!',
                    'message_sid' => $messageResponse->sid,
                    'status_code' => $messageResponse->status,
                    'to' => $messageResponse->to,
                ]);
            } else {
                print_r([
                    'status' => 'warning',
                    'message' => 'Mensagem não enviada com sucesso. Status: ' . $messageResponse->status,
                    'message_sid' => $messageResponse->sid,
                    'status_code' => $messageResponse->status,
                ]);
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
