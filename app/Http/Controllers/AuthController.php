<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Illuminate\Validation\ValidationException;
use Exception;
class AuthController extends Controller
{
    public function register(Request $request){
        try{
            $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

         $response = [
            'message' =>'Successfully Registered',
            'success' => true,
            'data' => $user
        ];
        return response()->json($response, 201);
        }catch (Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan saat get data category',
                'details' => $e->getMessage()
            ], 500); // Internal Server Error
        }
        

    }

    public function login(Request $request)
    {
        try{
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
    
            $user = User::where('email', $request->email)->first();
    
            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }
            $result = [
                'message' => 'Berhasil login',
                'succees' => true,
                'user' => $user,
                'token' => $user->createToken('login_api')->plainTextToken
            ];
            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal login',
                'error' => $e->getMessage()
            ], 500);
        }
      
    }
}