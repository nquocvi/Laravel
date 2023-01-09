<?php

namespace App\Http\Controllers\Admin\Users;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    public function index()
    {
       return view('admin.users.register', [
        'title' =>'Register'
       ]);
    }

    public function store(Request $request)
    {
        //dd($request->input());

        $this->validate($request, [
            'name' => 'required|string|max:255|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'phone' =>'required',
            'address' =>'required',
            'password' => 'required|min:3',
            'confirmPassword' =>'required|same:password'
        ]);
        
        $user = User::create(request(['name', 'email', 'password','phone','address']));
        
        Session::flash('success','Successful');
        
        return redirect()->to('admin/users/login');
    }
}
