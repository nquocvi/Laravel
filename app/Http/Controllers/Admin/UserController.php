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
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function viewUsers(Request $request)
    {
        $users = User::all();

        if ($request->has('search-name') && $request->get('search-name') != null) {
            $users = User::where('name', 'like', '%'. $request->get('search-name'). '%')->get();
        }
        
        if ($request->has('search-email') && $request->get('search-email') != null) {
            $users = User::where('email', 'like', '%'. $request->get('search-email'). '%')->get();
        }

        if ($request->has('role') && $request->get('role') != 'select') {
            $users = User::where('role', '=', (int) $request->get('role'))->get();
        }

        return view('admin.users.manage_user',[
            'title' => 'Manage User',
            'users'  => $users
        ]);
        
    }

    public function deleteUser($id)
    {
        if ($id != config('global.admin_role')) {

            User::where('id', $id)->delete();
            
            Session::flash('success','Deleted!');
            return back();
        }
        
        Session::flash('error','You can not delete admin');
        return back();
    }

    public function deleteMultipleUsers(Request $request)
    {
        if($request->get('users') != null){
            $id = $request->get('users');

            switch ($request->input('action')) {
    
                case 'Delete Selected':
                    foreach ($id as $user) 
                    {
                        if ((int) $user != config('global.admin_role')) {
                            User::where('id', (int) $user)->delete();
                        }else{
                            Session::flash('error','You can not delete admin');
                            Log::channel('daily')->info('You can not delete admin');
                            return back();
                        }
                    }
                    Session::flash('success','Deleted!');
                    return back();
                    break;
        
                case 'Export Selected':
    
                    $usersExport = new UsersExport($id);
                    return Excel::download($usersExport, 'users.xlsx');
                    break;
            }
        }else{
            Session::flash('error','Select user please!');
            return back();
        }
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
                Session::flash('warning',"Import: ".$total."---- Failed: ".$failed);
                Log::channel('daily')->info($import->failures());

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
    
}
