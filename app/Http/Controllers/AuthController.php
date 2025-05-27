<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    use ApiResponse;

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'gender' => $request->gender,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken($user->email);

        return $this->success('Registration successful', [
            'access_token' => $token->plainTextToken
        ]);
    }
    public function login(LoginRequest $request)
    {
        $user = User::where(['email' => $request->email])->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->unauthorized('Invalid credentials provided');
        }

        $token = $user->createToken($user->email);

        $user->tokens()
            ->latest()
            ->first()
            ->update([
                'expires_at' => now()->addDays(3),
            ]);


        return $this->success('Login successful', [
            'access_token' => $token->plainTextToken
        ]);
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return $this->success('Logout successful', []);
    }

    public function getUser(Request $request)
    {
        return $this->success('Profile loaded successfully', $request->user()->toArray());
    }
}
