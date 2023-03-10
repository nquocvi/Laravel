<?php

namespace App\Http\Controllers\Admin\Users;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    public function index()
    {
       return view('admin.users.register', [
        'title' => 'Register'
       ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'address' => 'required',
            'password' => 'required|min:3',
            'confirmPassword' => 'required|same:password'
        ]);
        
        User::create(request(['name', 'email', 'password', 'phone', 'address']));    
        Session::flash('success', 'Successful');
        return view('admin/users/login');
    }
}
