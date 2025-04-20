<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthControler extends Controller
{
    public function Regester(Request $request){
       $request->validate([
           'name' => 'required',
           'email' => 'required',
           'password' => 'required'
       ]) ;
       User::create([
           'name' => $request->name,
           'email' => $request->email,
           'password' => encrypt($request->password)
       ]);
    }
}
