<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validar que num_empleado esté presente y exista en la tabla u
        $validated = $request->validate([
            'num_empleado' => 'required|numeric|exists:users,num_empleado',
        ]); 

        // Buscar usuario por num_empleado
        $user = User::where('num_empleado', $validated['num_empleado'])->first();

        // Crear token de acceso
        $token = $user->createToken('auth_token')->plainTextToken;

        // Responder con token y datos del usuario
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        // Eliminar token actual para cerrar sesión
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada con éxito']);
    }

    public function user(Request $request)
    {
        // Devolver datos del usuario autenticado
        return response()->json($request->user());
    }
}

