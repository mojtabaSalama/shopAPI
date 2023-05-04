<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
public function register (Request $request ){

    $field=$request->validate ([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:6', 'confirmed'],
        'phoneNumber' => ['required', 'integer', 'digits:10']
    ]);
   $user= User::create( [
        'name' => $field['name'],
        'email' => $field['email'],
        'password' =>Hash::make($field['password']),
        'phoneNumber' => $field['phoneNumber'],
    ]);
    
    return response([

        'user'=> $user,
        'token'=>$user->createToken('secrete')->plainTextToken
    ],200);
} 
public function logout(Request $request){
auth()->user()->tokens()->delete();
return[
    'message'=>'You logged out'
];


}
public function login (Request $request ){

    $field=$request->validate ([
        
        'email' => ['required', 'string', 'email', 'max:255', ],
        'password' => ['required', 'string', 'min:6', ],
    ]);
   $user=User::where('email',$field['email'])->first();
   if(!$user|| !Hash::check($field['password'],$user->password)){
    return response(['message'=>'wrong data'],401);
   }
   $token = $user->createToken('secrete')->plainTextToken;

    return response([

        'user'=> $user,
        'token'=>$token   ],200);
} 

}
