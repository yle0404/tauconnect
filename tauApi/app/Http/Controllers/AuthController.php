<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Laravel\Sanctum;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    public function login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', htmlspecialchars($request['email']))->first();

        if(!$user || !Hash::check($request['password'], $user->password)){
            return response([
                'message' => 'invalid email and password'
            ], 400);
        }

        $token = $user->createToken('myAppToken')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ], 200);
    }
    public function logout(Request $request){
        auth()->user()->tokens()->delete();

        return response([
            'message' => 'logged out'
        ], 200);
    }

    public function getProfile(Request $request){
        $token = PersonalAccessToken::findToken($request->bearerToken());
        $id = $token->tokenable->id;

        $user = User::where('id', $id)->first();

        return response($user, 200);
    }

    public function updateProfilePicture(Request $request){
        $request->validate([
            'image' => 'required',
        ]);

        $filepath = uniqid().".jpg";
        file_put_contents($filepath, base64_decode($request['image']));

        $token = PersonalAccessToken::findToken($request->bearerToken());
        $id = $token->tokenable->id;

        $user = User::where('id', $id)->first();

        $user->update([
            'profile_picture' => '../../tauApi/public/'.$filepath,
        ]);

        return response([
            'profile_picture' => $user->profile_picture
        ], 200);
    }

    public function updateProfile(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        ]);


        $token = PersonalAccessToken::findToken($request->bearerToken());
        $id = $token->tokenable->id;
        $user = User::where('id', $id)->first();

        $name = htmlspecialchars($request['name']);
        $email = htmlspecialchars($request['email']);
        $password = bcrypt($request['password']);

        $user->update([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);


        return response($user, 200);
    }
}
