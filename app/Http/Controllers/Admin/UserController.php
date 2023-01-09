<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Imports\UsersImport;


class UserController extends Controller
{
    public function viewUsers(Request $request)
    {
        //$data = User::all();


        $userQuery = User::query();
        if ($request->has('search')) {
            $userQuery->where('name', 'like', '%'. $request->get('search'). '%');
        }
        $users = $userQuery->paginate(7);
        
        // dd($users);
        // if($request->filled('search')){
        //     $data = User::where('id', 1)->get();
        //     //dd(User::find(1)->get());
        //     //dd(User::search($request->search)->get()->toArray());
        //     // $data = User::paginate(5);
        // }else{
        //     $data = User::paginate(10);
        // }

        return view('admin.users.manage_user',[
            'title' => 'Manage User',
            'users'  => $users
        ]);
    }

    public function deleteUser($id)
    {
        if ($id!='1') {
            $res=User::where('id',$id)->delete();
            $data = User::paginate(20);
            return view('admin.users.manage_user',[
                'title' => 'Manage User',
                'users'  => $data
            ]);
        }

        $data = User::all();

        return view('admin.users.manage_user',[
            'title' => 'Manage User',
            'users'  => $data
        ]);
    }

    public function viewUser($id, Request $request)
    {
        $user = User::find($id);

        return view('admin.users.edit_user',[
            'title' => 'Edit User',
            'user' => $user
        ]);
    }

    public function updateUser($id, Request $request)
    {
        $user = User::findOrFail($id);
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->created_at = $request->get('created_at');

        $user->save();

        $user = User::find($id);
        Session::flash('success','Successful update!');
        
        return view('admin.users.edit_user',[
            'title' => 'Edit User',
            'user' => $user
        ]);
    }

    public function import() 
    {
        
        Excel::import(new UsersImport,request()->file('file'));
               
        return back();
    }

    public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}
