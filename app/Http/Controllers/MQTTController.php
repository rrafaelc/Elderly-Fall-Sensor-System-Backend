<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Support\Facades\Log;
use App\Models\SensorData;

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
        } catch (\Exception $e) {
            Log::error('Erro ao salvar dados no MySQL: ' . $e->getMessage());
        }
    } else {
        // Loga um aviso com os dados inválidos recebidos para análise
        Log::warning('Dados inválidos recebidos: ' . $message);
    }
}





// //teste inicial
//     public static function processData1($message)
//     {
//         // Decodificar a mensagem JSON
//         $data = json_decode($message, true);

//         // Verifique se a decodificação foi bem-sucedida e se os dados estão no formato esperado
//         if (is_array($data) && isset($data[0]['aceleration'], $data[0]['rotation'], $data[0]['time'], $data[0]['fall'],$data[0]['level'] )) {
//             // Criar um novo registro no MongoDB
//             $sensorData = new Sensor();
//             $sensorData->aceleration = $data[0]['aceleration'];
//             $sensorData->rotation = $data[0]['rotation'];
//             $sensorData->time = $data[0]['time'];
//             $sensorData->fall = $data[0]['fall'];
//             $sensorData->level = $data[0]['level'];

//             // Salvar no MongoDB
//             if ($sensorData->save()) {
//                 // Logar a mensagem de sucesso
//                 Log::info('Dados salvos no MongoDB: ' . json_encode($data));
//             } else {
//                 // Logar erro se falhar ao salvar
//                 Log::error('Erro ao salvar dados no MongoDB');
//             }
//         } else {
//             // Logar um aviso caso a mensagem não contenha os dados esperados
//             Log::warning('Dados inválidos recebidos: ' . $message);
//         }
//     }

//     public static function processDataMongo($message)
// {
//     // Decodificar a mensagem JSON
//     $data = json_decode($message, true);

//     // Verifique se a decodificação foi bem-sucedida e se os dados estão no formato esperado
//     if (is_array($data) && isset($data['serial_number'], $data['event_type'], $data['is_fall'], $data['is_impact'], $data['acceleration'], $data['gyroscope'])) {
//         // Criar um novo registro no MongoDB
//         $sensorData = new Sensor();
//         $sensorData->serial_number = $data['serial_number'];
//         $sensorData->event_type = $data['event_type'];
//         $sensorData->is_fall = $data['is_fall'];
//         $sensorData->is_impact = $data['is_impact'];
//         $sensorData->acceleration = $data['acceleration'];
//         $sensorData->gyroscope = $data['gyroscope'];

//         // Salvar no MongoDB
//         if ($sensorData->save()) {
//             // Logar a mensagem de sucesso
//             Log::info('Dados salvos no MongoDB: ' . json_encode($data));
//         } else {
//             // Logar erro se falhar ao salvar
//             Log::error('Erro ao salvar dados no MongoDB');
//         }
//     } else {
//         // Logar um aviso caso a mensagem não contenha os dados esperados
//         Log::warning('Dados inválidos recebidos: ' . $message);
//     }
// }

}
