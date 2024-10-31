<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Support\Facades\Log;

class MQTTController extends Controller
{
    public static function processData($message)
    {
        // Decodificar a mensagem JSON
        $data = json_decode($message, true);

        // Verifique se a decodificação foi bem-sucedida e se os dados estão no formato esperado
        if (is_array($data) && isset($data[0]['aceleration'], $data[0]['rotation'], $data[0]['time'], $data[0]['fall'],$data[0]['level'] )) {
            // Criar um novo registro no MongoDB
            $sensorData = new Sensor();
            $sensorData->aceleration = $data[0]['aceleration'];
            $sensorData->rotation = $data[0]['rotation'];
            $sensorData->time = $data[0]['time'];
            $sensorData->fall = $data[0]['fall'];
            $sensorData->level = $data[0]['level'];

            // Salvar no MongoDB
            if ($sensorData->save()) {
                // Logar a mensagem de sucesso
                Log::info('Dados salvos no MongoDB: ' . json_encode($data));
            } else {
                // Logar erro se falhar ao salvar
                Log::error('Erro ao salvar dados no MongoDB');
            }
        } else {
            // Logar um aviso caso a mensagem não contenha os dados esperados
            Log::warning('Dados inválidos recebidos: ' . $message);
        }
    }
}
