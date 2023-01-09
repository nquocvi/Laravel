<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class LoginController extends Controller
{
    public function index()
    {
        return View::make('admin.users.login',['title' => 'Login']);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email:filter',
            'password' => 'required'
        ]);

        if (Auth::attempt([
            'email' => $request->input('email'),
            'password' =>$request->input('password'),
            'role'=> '1'
            ], $request->input('remember'))) {
            return redirect()->route('admin');
        } elseif (Auth::attempt([
            'email' => $request->input('email'),
            'password' =>$request->input('password'),
            'role'=> '0'
            ], $request->input('remember'))) {
            return redirect()->route('user');
        } else {
            Session::flash('error','Email or Password incorect!');
            return redirect()->back();
        }
    }
}
