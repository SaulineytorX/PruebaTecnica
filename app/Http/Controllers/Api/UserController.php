<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;


use App\Models\User;
use App\Models\Card;
use App\Models\UserAction;


class UserController extends Controller
{


    public function getToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('api-token')->plainTextToken;
            $expiration = now()->addMinutes(5);

            return response()->json(['token' => $token, 'expiration' => $expiration]);
        }

        return response()->json(['message' => 'Invalid email or password'], 404);
    }


    public function createUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required|string',
            'phone' => 'required|integer',
            'password' => 'required|string',
            'Consent_ID2' => 'required|boolean',
            'Consent_ID3' => 'required|boolean',
        ]);
        //CREACION DE USUARIO
        $user = new User();
        $user->email = $request->email;
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        //Consent_ID2 y Consent_ID3 se generan automaticamente
        //Consent_ID2
        do {
            $randomString = Str::random(30);
        } while (User::where('Consent_ID2', $randomString)->exists());
        $user->Consent_ID2 = $randomString;
        $user->Consent_ID2_status = $request->Consent_ID2;
        //Consent_ID3
        do {
            $randomString = Str::random(30);
        } while (User::where('Consent_ID3', $randomString)->exists());
        $user->Consent_ID3 = $randomString;
        $user->Consent_ID3_status = $request->Consent_ID3;
        //Fin de generacion automatica

        $user->save();
        //FIN DE CREACION DE USUARIO

        //CREACION DE CARD CON EL CONSENT_ID1
        //Generamos el consent_id1 del modelo Card para el usuario creado
        do {
            $randomString = Str::random(30);
        } while (Card::where('Consent_ID1', $randomString)->exists());
        //Guardamos el consent_id1 en el modelo Card
        $card = new Card();
        $card->Consent_ID1 = $randomString;
        $card->user_id = $user->id;
        $card->save();
        //FIN DE CREACION DE CARD CON EL CONSENT_ID1

        if (!$user) {
            return response()->json(['response' => false, 'message' => 'User not created'], 400);
        }
        return response()->json(['response' => true, 'message' => 'User created successfully', 'id_user' => $user->id]);
    }

    public function updateUser(Request $request)
    {
        $request->validate([
            'id_user' => 'required|integer',
            'email' => 'required|email',
            'name' => 'required|string',
            'phone' => 'required|integer',
            'password' => 'required|string',
            'Consent_ID2' => 'required|boolean',
            'Consent_ID3' => 'required|boolean',
        ]);
        //ACTUALIZACION DE USUARIO
        $user = User::find($request->id_user);
        $user->email = $request->email;
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        //Consent_ID2 y Consent_ID3 se generan automaticamente
        //Consent_ID2
        do {
            $randomString = Str::random(30);
        } while (User::where('Consent_ID2', $randomString)->exists());
        $user->Consent_ID2 = $randomString;
        $user->Consent_ID2_status = $request->Consent_ID2;
        //Consent_ID3
        do {
            $randomString = Str::random(30);
        } while (User::where('Consent_ID3', $randomString)->exists());
        $user->Consent_ID3 = $randomString;
        $user->Consent_ID3_status = $request->Consent_ID3;
        //Fin de generacion automatica

        $user->save();
        //FIN DE ACTUALIZACION DE USUARIO
        //REGISTRO DE ACCION DEL USUARIO
        if (isset($request->Consent_ID2)) {
            UserAction::create([
                'user_id' => $user->id,
                'action' => $request->Consent_ID2 ? 'cambio a true' : 'cambio a false',
                'consent_id' => 'Consent_ID2',
                'bitacora_date' => now(),
            ]);
        }

        if (isset($request->Consent_ID3)) {
            UserAction::create([
                'user_id' => $user->id,
                'action' => $request->Consent_ID3 ? 'cambio a true' : 'cambio a false',
                'consent_id' => 'Consent_ID3',
                'bitacora_date' => now(),
            ]);
        }
        //FIN DE REGISTRO DE ACCION DEL USUARIO



        if (!$user) {
            return response()->json(['response' => false, 'message' => 'User not updated'], 400);
        }
        return response()->json(['response' => true, 'message' => 'User updated successfully', 'id_user' => $user->id]);
    }

    public function deleteUser(Request $request)
    {
        $request->validate([
            'id_user' => 'required|integer',
        ]);
        //ELIMINACION DE USUARIO
        $user = User::find($request->id_user);
        $user->delete();
        //FIN DE ELIMINACION DE USUARIO

        if (!$user) {
            return response()->json(['response' => false, 'message' => 'User not deleted'], 400);
        }
        return response()->json(['response' => true, 'message' => 'User deleted successfully', 'id_user' => $user->id]);
    }
}
