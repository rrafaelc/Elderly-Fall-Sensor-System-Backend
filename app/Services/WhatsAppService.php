<?php

namespace App\Services;

use Twilio\Rest\Client;

class WhatsAppService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(
            env('TWILIO_SID'),
            env('TWILIO_AUTH_TOKEN')
        );
    }

    public function sendMessage($to, $message)
    {
        return $this->twilio->messages->create(
            "whatsapp:" . $to, // Número do destinatário no formato WhatsApp
            [
                "from" => env('TWILIO_WHATSAPP_NUMBER'),
                "body" => $message
            ]
        );
    }
}
