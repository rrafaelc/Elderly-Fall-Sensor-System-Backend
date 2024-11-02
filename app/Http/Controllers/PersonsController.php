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

        $person = Person::create([
            'user_id' => $request->input('user_id'),
            'name' => $request->input('name'),
            'cpf' => $request->input('cpf'),
            'email' => $request->input('email')

        ]);
        return $person;
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
