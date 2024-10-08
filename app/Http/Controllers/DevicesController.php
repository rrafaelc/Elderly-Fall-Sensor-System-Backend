<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\PersonDevice;

class DevicesController extends Controller
{
    public function __construct(Device $device)
    {
        $this->device = $device;
    }


    public function create(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id', // Verifique se o usuário existe
            'person_id' => 'required|exists:persons,id', // Verifique se a pessoa existe
            'name' => 'required|string',
        ]);

        // Cria o dispositivo
        $device = Device::create(['name' => $request->name]);

        // Associa o usuário, pessoa e dispositivo na tabela intermediária
        PersonDevice::create([
            'user_id' => $request->user_id,
            'person_id' => $request->person_id,
            'device_id' => $device->id,
        ]);

        return response()->json(['message' => 'Device created and associated successfully.', 'device' => $device]);
    }



    public function index()
    {

        return response()->json($this->device->all(), 200);
    }

    public function store(Request $request)
    {

       // $request->validate($this->user->rules(), $this->user->feedback());
        $device = device::create([
            'name' => $request->input('name'),
            'whatsapp_number' => $request->input('whatsapp_number'),

        ]);
        return $device;
    }

    public function show($id)
    {
        $user = $this->device->find($id);
        if ($user === null) {
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

        $device->device_name = ($request->input('device_name'));
        $device->whatsapp_number = ($request->input('whatsapp_number'));
        $device->save();
        //$user->update($request->all());
        return response()->json($device, 200);
    }

    public function destroy(Device $device)
    {
        $device->delete();
        return response()->json(['sucess' => true]);
    }

    //ALTERNATIVO PARA O METODO CREATE
     // Método para associar um dispositivo a uma pessoa
     public function associate(Request $request, $deviceId)
     {
         $request->validate([
             'person_id' => 'required|exists:persons,id', // Valida se o person_id existe
         ]);

         $device = Device::findOrFail($deviceId);
         $device->persons()->attach($request->person_id);

         return response()->json(['message' => 'Device associated successfully.']);
     }
}
