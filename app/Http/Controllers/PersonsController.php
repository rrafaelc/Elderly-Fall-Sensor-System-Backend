<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Person;


class PersonsController extends Controller
{
    public function __construct(Person $person)
    {
        $this->person = $person;
    }

    public function index()
    {

        return response()->json($this->person->all(), 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'name' => 'required|string|max:255',
            'rg' => 'nullable|string|max:20',
            'cpf' => 'required|string|max:20|unique:persons',
            'date_of_birth' => 'nullable|date',
            'blood_type' => 'nullable|string|max:3',
            'street' => 'nullable|string|max:255',
            'street_number' => 'nullable|string|max:10',
            'neighborhood' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:2',
            'zip_code' => 'nullable|string|max:10',
            'conditions' => 'nullable|string'
        ]);

        $person = Person::create($validatedData);

        return response()->json($person, 201);
    }


    public function show($id)
    {
        $person = $this->person->find($id);
        if ($person === null) {
            return response()->json(['erro' => 'Usuario não encontrado.'], 404);
        }
        return response()->json($person, 200);
    }

    public function update(Request $request, $id)
    {

        $person = $this->person->find($id);
        if ($person === null) {
            return response()->json(['erro' => 'não foi possivel atualizar, usuário não encontrado.'], 404);
        }
        $person->update($request->all());

        $person->save();

        return response()->json($person, 200);
    }

    public function destroy(Person $person)
    {
        $person->delete();
        return response()->json(['sucess' => true]);
    }
}
