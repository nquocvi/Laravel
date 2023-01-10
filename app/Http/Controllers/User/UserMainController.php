<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class UserMainController extends Controller
{
    public function index()
    {
        return view('user.home',[
            'title' => 'Homepage'
        ]);
    }
}
