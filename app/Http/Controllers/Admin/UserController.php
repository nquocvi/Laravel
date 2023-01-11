<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use App\Models\Failures;
use App\Models\FailuresDetail;

class UserController extends Controller
{
    public function viewUsers()
    {
        $users = User::paginate(config('global.pagination_records'));

        return view('admin.users.manage_user',[
            'title' => 'Manage User',
            'users'  => $users
        ]);
    }

    public function deleteUser($id)
    {
        if ($id != '1') {
            User::where('id', $id)->delete();
            $data = User::paginate(config('global.pagination_records'));

            return view('admin.users.manage_user',[
                'title' => 'Manage User',
                'users'  => $data
            ]);
        }

        $data = User::paginate(config('global.pagination_records'));

        return view('admin.users.manage_user',[
            'title' => 'Manage User',
            'users'  => $data
        ]);
    }

    public function deleteMultipleUsers(Request $request)
    {

        $id = $request->get('users');

        foreach ($id as $user) 
		{
			 User::where('id', (int) $user)->delete();
		}
        $data = User::paginate(config('global.pagination_records'));

        return view('admin.users.manage_user',[
            'title' => 'Manage User',
            'users'  => $data
        ]);
    }

    public function viewUser($id)
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
        $failures = Failures::paginate(config('global.pagination_records'));

        return view('admin.users.import_user',[
            'title' => 'Import User',
            'failures' => $failures
        ]);
    }

    public function import(Request $request) 
    {   
        if (count($request->all()) == 1) {
            Session::flash('error','Select file please!');
            return back();
        }else{
            $failed = 0;
            $description = '';

            $file = $request->file('file'); 
            
            $import = new UsersImport;
            $import->import($file);
            $total = count($import->toArray($file)[0]);

            if ($import->failures()->isNotEmpty()) {
                $failed = count($import->failures());
                if($failed > 0) {
                    $description = 'view detail';
                }

                $failures = new Failures();
                $failures->total = $total;
                $failures->failed = $failed;
                $failures->detail_failures = $description;
                $failures->save();

                foreach ($import->failures() as $failure){
                    
                    $failures_detail = new FailuresDetail();
                    $failures_detail->line = $failure->row();
                    $failures_detail->attribute = $failure->attribute();
                    $failures_detail->erorr = implode(" ",$failure->errors()); 
                    $failures_detail->failures_id = $failures->id;
                    $failures_detail->value = $failure->values()[$failure->attribute()];
                    $failures_detail->save();
                }

                return back()->withFailures([
                    'fail' => $import->failures(),
                    'total' => $total
                ]);

            }

            $failures = new Failures();
            $failures->total = $total;
            $failures->failed = $failed;
            $failures->detail_failures = $description;
            $failures->save();

            Session::flash('success','Excel file imported successfully');

            return back()->withTotal($total);
        }
        
    }

    public function detailImport($id) 
    {
        $failures = FailuresDetail::where('failures_id', '=', $id)->paginate(config('global.pagination_records'));
        return view('admin.users.import_detail',[
            'title' => 'Detail Import',
            'failures' => $failures
        ]);
    }

    public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }


    
}
