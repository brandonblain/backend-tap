<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\RecoverCredentialsMail;

class AuthController extends Controller
{
    
    public function login(LoginRequest $request): JsonResponse
    {
        
        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Las credenciales proporcionadas son incorrectas.'
            ], 401);
        }

        $profiles = Profile::whereIn('_id', $user->profile_ids ?? [])->get();
        
        $sections = $profiles->pluck('sections')->flatten()->unique()->values()->toArray();

        $token = $user->createToken('auth_token', $sections)->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso.',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'code' => $user->code,
                'name' => $user->name,
                'username' => $user->username,
                'profile_picture' => $user->profile_picture,
                'sections' => $sections
            ]
        ], 200);
    }

    public function logout(Request $request): JsonResponse
{
    $request->user()->currentAccessToken()->delete();
    return response()->json([
        'message' => 'Sesión cerrada exitosamente. El token ha sido revocado.'
    ], 200);
}

    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'username' => 'required|email|exists:users,username'
        ], [
            'username.required' => 'El correo electrónico (username) es obligatorio.',
            'username.email' => 'Por favor, introduce un correo válido.',
            'username.exists' => 'El correo electrónico ingresado no está registrado.'
        ]);

        $user = User::where('username', $request->username)->first();

        $temporaryPassword = Str::random(8);

        //Encriptado de contraseña Nueva
        $user->password = Hash::make($temporaryPassword);
        $user->save();

        //Enviar las credenciales al correo registrado 
        Mail::to($user->username)->send(new RecoverCredentialsMail($user->name, $temporaryPassword));

        return response()->json([
            'message' => 'Las nuevas credenciales han sido enviadas con éxito al correo registrado.'
        ], 200);
    }
}