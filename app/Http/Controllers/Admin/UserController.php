<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Imports\UsersImport;

use function PHPUnit\Framework\countOf;

class UserController extends Controller
{
    public function viewUsers(Request $request)
    {
        $userQuery = User::query();
        if ($request->has('search')) {
            $userQuery->where('name', 'like', '%'. $request->get('search'). '%');
        }
        $users = $userQuery->paginate(7);
        
        return view('admin.users.manage_user',[
            'title' => 'Manage User',
            'users'  => $users
        ]);
    }

    public function deleteUser($id)
    {
        if ($id != '1') {
            $res=User::where('id', $id)->delete();
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

    public function importUser() 
    {
        
        return view('admin.users.import_user',[
            'title' => 'Import User',
        ]);
    }

    public function import(Request $request) 
    {   
        $file = $request->file('file'); 
        
        $import = new UsersImport;
        $import->import($file);
        
        
        //dd($import->failures());

        //$total = $import::beforeImport($import($file));

        if ($import->failures()) {
            return back()->withFailures($import->failures());
        }
               
        Session::flash('success','Excel file imported successfully');
        // return back()->withTotal('total',$total);
    }

    public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
    
}
