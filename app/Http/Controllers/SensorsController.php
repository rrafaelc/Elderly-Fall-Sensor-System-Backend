<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use Illuminate\Http\Request;

class SensorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->query('page', 1); // Obtém o parâmetro 'page' da query string, ou usa 1 por padrão
        $perPage = 10; // Número de itens por página

        // Consulta e paginação, ordenando do mais recente para o mais antigo
        $sensors = Sensor::orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json($sensors); // Retorna os dados paginados como JSON
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'aceleration' => 'required|numeric',
            'rotation' => 'required|numeric',
            'time' => 'required|string', // Validar como string
        ]);
        $sensor = Sensor::create($request->all());

        return response()->json($sensor, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $sensor = Sensor::find($id);

        if (!$sensor) {
            return response()->json(['message' => 'Sensor not found'], 404);
        }

        return response()->json($sensor);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sensor $sensor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sensor $sensor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sensor $sensor)
    {
        //
    }
}
