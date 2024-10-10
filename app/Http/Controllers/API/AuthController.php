<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'password' => Hash::make($validatedData['password']),
        ]);

        // Generar un nuevo token con un tiempo de expiración de 60 minutos
        $tokenResult = $user->createToken('authToken', ['*']);
        $token = $tokenResult->plainTextToken;
        return response()->json(['message' => 'Usuario registrado con éxito', 'token' => $token], 200);
    }

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('name', $validatedData['name'])->first();

        if (!$user || !Hash::check($validatedData['password'], $user->password)) {
            throw ValidationException::withMessages([
                'name' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Invalidar tokens antiguos
        $user->tokens()->delete();

        // Generar un nuevo token con un tiempo de expiración de 60 minutos
        $tokenResult = $user->createToken('authToken', ['*']);
        $token = $tokenResult->plainTextToken;

        return response()->json(['message' => 'Inicio de sesión exitoso', 'token' => $token], 200);
    }

    public function logout(Request $request)
    {
        // Eliminar el token actual
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente'], 200);
    }

    public function isValidBuyer(Request $request): bool
    {
        return $request->user()?->status === 'comprador';
    }

    public function isProvider(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(['isProvider' => $request->user()?->provider === 1]);
    }

    public function getAuthenticatedUserName(Request $request)
    {
        $user = $request->user();
        if ($user) {
            return $user->name;
        }
        return null; // o algún otro valor predeterminado si no hay usuario autenticado
    }
}
