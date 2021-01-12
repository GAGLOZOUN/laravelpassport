<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Auth;
//use Carbon\Carbon;
use App\User;


class AuthController extends Controller
{
    public function register(Request $request)
    {
      $validatedData = $request->validate([
        'name'=>'required|max:55',
        'email'=>'email|required|unique:users',
        'password'=>'required|confirmed',

      ]);

      $validatedData['password'] = bcrypt($request->password);
      $user = User::create($validatedData);

      $accessToken = $user->createToken('authToken')->accessToken;

      return response(['user'=>$user,  'access_token'=>$accessToken]);
    }



    public function login(Request $request)
    {
        $loginData = $request->validate([
          'email'=>'email|required',
          'password'=>'required',
        ]);

        $credentials = $request->only(['email','password']);
        var_dump($credentials);
    if(!Auth::attempt($credentials)){
        return response(['message'=>'Invalid credentials']);
    }

    $accessToken = auth()->user()->createToken('authToken')->accessToken;

    return response(['user'=> auth()->user(),  'access_token' => $accessToken]);

    }
    /*public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }*/
}
