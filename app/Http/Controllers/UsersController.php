<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {

        return response()->json($this->user->all(), 200);
    }

    public function store(Request $request)
    {

       // $request->validate($this->user->rules(), $this->user->feedback());
        $user = User::create([
            'name' => $request->input('name'),
            'whatsapp_number' => $request->input('whatsapp_number'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        return $user;
    }

    public function show($id)
    {
        $user = $this->user->find($id);
        if ($user === null) {
            return response()->json(['erro' => 'Usuario não encontrado.'], 404);
        }
        return response()->json($user, 200);
    }

    public function update(Request $request, $id)
    {

        $user = $this->user->find($id);
        if ($user === null) {
            return response()->json(['erro' => 'não foi possivel atualizar, usuário não encontrado.'], 404);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();
        //$user->update($request->all());
        return response()->json($user, 200);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['sucess' => true]);
    }
}
