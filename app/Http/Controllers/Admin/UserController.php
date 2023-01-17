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
use App\Imports\ValidateCsvFile;

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

    
    public function export() 
    {
        $usersExport = new UsersExport();
        return Excel::download($usersExport, 'users.xlsx');
    }
    

    public function import(Request $request) 
    {   
        if (count($request->all()) == 1) {
            Session::flash('error','Select file please!');
            return back();
        }else{
            $file = $request->file('file'); 
            $import = new UsersImport;
            $total = count($import->toArray($file)[0]);
            $failed = 0;
            $description = '';

            $validator = new ValidateCsvFile();
            Excel::import($validator,$file);

            if (count($validator->errors)) {
                $failed = count($validator->errors);
                $description = 'view detail';

                Session::flash('warning',"Import: ".$total."---- Failed: ".$failed);
               
                
                $log = new Failures();
                $log->total = $total;
                $log->failed = $failed;
                $log->detail_failures = $description;
                $log->save();

                $failures = $validator->errors;

                foreach ($failures as $key => $error) {
                    $errors[$key] = $key;
                    $col = '';

                    if(key($error->failed()) == 1){
                        $col = 'email';
                    }
                    if(key($error->failed()) == 0){
                        $col = 'name';
                    }
                    if(key($error->failed()) == 2){
                         $col = 'password';
                    }
      
                    $failures_detail = new FailuresDetail();
                    $failures_detail->line = $key;
                    $failures_detail->attribute = $col;
                    $failures_detail->erorr = $error->errors(); 
                    $failures_detail->failures_id = $log->id;
                    $failures_detail->value = implode(" ",$error->invalid());
                    $failures_detail->save();
                    
                    Log::channel('daily')->info($failures_detail);
                }
                return back();  

            } elseif (!$validator->isValidFile) {
                return redirect()->back();
            }else{
                (new UsersImport())->queue($file);

                $log = new Failures();
                $log->total = $total;
                $log->failed = $failed;
                $log->detail_failures = $description;
                $log->save();
    
                Session::flash('success','Excel file imported successfully');
                return back()->withTotal($total);  
            }


        }
        
    }

    public function importCSV(Request $request) 
    {   
        
        if (count($request->all()) == 1) {
            Session::flash('error','Select file please!');

            return back();
        }else{

            $data = array_map('str_getcsv', file($request->file('file')));
            $header = $data[0];
            unset($data[0]);
            $logs= [];
            $users= [];
            $list = User::all()->toArray();

            foreach ($data as $value){
                $userData = array_combine($header, $value);
               
                if ($this->checkData($userData, $list)) {
                    array_push($users, $userData);
                } else {
                    array_push($logs,"err");
                }
                
            }
        
            if (count($logs) > 0){
                Session::flash('warning'," Failed: ".count($logs));

                return back();
            }else{
                $break_data = array_chunk($users, 1000, true);
                foreach ($break_data as $data) {
                   User::insert($data);
                }
                Session::flash('success','Excel file imported successfully');

                return back()->withTotal(count($users));  
            }
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

    function checkData($data, $list,)
    {
        $logs = [];

        if (strlen($data['name']) < 5) {
            array_push($logs,"Name too short");
        }if (strlen($data['password']) < 5) {
            array_push($logs,"Password too short");
        }

        foreach($list as $l){
            if($l['name'] == $data['name']){
                array_push($logs,"Name already taken");
            }
            if($l['email'] == $data['email']) {
                array_push($logs,"Email already taken");
            }
        }

        if(count($logs) < 1){
            return true;
        }
        return false;
       
    }
    
}
