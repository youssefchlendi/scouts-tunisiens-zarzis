<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticationController extends Controller
{
    public function register(RegisterRequest $request){
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $role = Role::find($request->role);
        if($request->troupe){
            $user->roless()->attach($role,['troupe_id'=>$request->troupe]);
        }else{
            $user->roless()->attach($role);
        }

        return response()->json([
            'success'   => true,
            'message'   => 'User created',
            'data'      => $user
        ]);

    }

    public function login(LoginRequest $request){
        $input = $request->all();
        if (!Auth::attempt($input)) {
            return response()->json([
                'success'   => false,
                'message'   => 'المعلومات المدخلة غير صحيحة',
                'data'      => []
            ],401);
        }

        return response()->json([
            'success'   => true,
            'token'     => Auth::user()->createToken('token')->plainTextToken,
            'user'      => Auth::user()
        ],200);
    }

    public function unique($field){
        $user = User::where('email', $field)->first();
        $user1 = User::where('cin', $field)->first();
        if($user || $user1){
            return response()->json([
                'success'   => false,
                'message'   => 'User already exists',
                'data'      => []
            ],201);
        }else{
            return response()->json([
                'success'   => true,
                'message'   => 'User not exists',
                'data'      => []
            ]);
        }

    }

}
