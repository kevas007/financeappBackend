<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     * 
     */

     public function store(Request $request)
     {
         try {
             $attr = $request->validate([
                 'name' => 'required|string',
                 'email' => 'required|string|email|unique:users',
                 'password' => 'required|string|min:4'
             ]);
             
             // Crée un nouvel utilisateur avec les données validées
             $user = User::create([
                 'name' => $attr['name'],
                 'email' => $attr['email'],
                 'password' => Hash::make($attr['password'])
             ]);
             
             // Crée un token d'authentification pour le nouvel utilisateur
             $token = $user->createToken('API Token')->plainTextToken;
             
             return response()->json([
                 'message' => 'User created successfully',
                 'status' => 200,
                 'user' => $user,
                 'token' => $token
             ], 200);
         } catch (\Exception $e) {
             // Log the error or handle it accordingly
             return response()->json([
                 'message' => 'An error occurred: ' . $e->getMessage(),
                 'status' => 500
             ], 500);
         }
     }
     

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): Response
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
