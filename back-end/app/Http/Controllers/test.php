<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class test extends Controller
{
    public function test(Request $request)
    {
        // $leaders = User::whereHas('roles')->with('roles.troupe')->first();
        $leaders = User::with('roles.troupe')->with('roles.role')->
            whereHas('roles.role',function ($query) {
            $query->where('ename','like', '%Leader%');
        })->get();
            return response()->json($leaders, 200);

        // return response()->json($leaders, 200);
    }
}
