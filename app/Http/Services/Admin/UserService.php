<?php

namespace App\Http\Services\Admin;

use App\Models\User;
use App\Models\Failures;
use App\Models\FailuresDetail;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class UserService 
{
    public function getUsers($request)
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

        return $users;
    }

    public function getUser($id)
    {
        $user = User::find($id);
        return $user;
    }

    public function deleteUser($id)
    {
        if ($id != config('global.admin_role')) {
            User::where('id', $id)->delete(); 
            Session::flash('success','Deleted!');
        }else{ 
            Session::flash('error','You can not delete admin');
        }
    }

    public function handleMultipleUsers($request)
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
                        }
                    }
                    Session::flash('success','Deleted!');
                    break;
        
                case 'Export Selected':
                    $ids = array_map('intval', $id);
                    $usersExport = new UsersExport($ids);
                    return Excel::download($usersExport, 'users.xlsx');
            }
        }else{
            Session::flash('error','Select user please!');
        }
    }

    public function updateUser($id, $request)
    {
        $user = User::findOrFail($id);
        $user->name = $request->get('name');
        $user->email = $request->get('email');
        $user->created_at = $request->get('created_at');
        $user->save();

        $user = User::find($id);

        Session::flash('success','Successful update!');
        return $user;
    }

    public function importUser() 
    {
        $failures = Failures::paginate(config('global.pagination_records'));
        return $failures;
    }


    public function importCsv($request) 
    {   
        $timeStart = microtime(true);
        if (count($request->all()) == 1) {
            Session::flash('error','Select file please!');
        }else{
            $data = array_map('str_getcsv', file($request->file('file')));
            $header = $data[0];
            unset($data[0]);
            $total = count($data);
            $logs = [];
            $users = [];
            $list = User::all()->toArray();
            $i = 1;
            
            foreach ($data as $value) {
                $userData = array_combine($header, $value);

                $check = $this->checkData($userData, $list, $i);

                if (count($check['log']) < 1) {
                    array_push($users, $userData);
                } else {
                    array_push($logs,$check);
                }
                $i++;
            }

            if (count($logs) > 0) {
                $failures = new Failures();
                $failures->total = $total;
                $failures->failed = count($logs);
                $failures->detail_failures = 'view detail';
                $failures->save();
                
                foreach( $logs as $log) {
                    $value = [];
                    foreach(array_keys($log['log']) as $key) {
                        array_push($value,$log['data'][$key]);
                    }                
                    $failures_detail = new FailuresDetail();
                    $failures_detail->line = $log['row'];
                    $failures_detail->attribute = implode(" ", array_keys($log['log']));
                    $failures_detail->erorr = implode(" --- ", $log['log']); 
                    $failures_detail->failures_id = $failures->id;
                    $failures_detail->value = implode(" --- ", $value);
                    $failures_detail->save();

                    Log::channel('daily')->info($failures_detail);
                }
                $sec = number_format((float)microtime(true) - $timeStart, 2, '.', '');
                Session::flash('warning','Import: '.$total. '---- Failed: '.count($logs).' in '.$sec.'s');
            }else{
                $break_data = array_chunk($users, config('global.chunk'), true);

                foreach ($break_data as $data) {
                   User::insert($data);
                }
                
                $log = new Failures();
                $log->total = $total;
                $log->failed = count($logs);
                $log->detail_failures = '';
                $log->save();

                $sec = number_format((float)microtime(true) - $timeStart, 2, '.', '');
                
                Session::flash('success','Excel file imported '.$total. ' rows successfully'." in ".$sec."s");
            }
        }
    }
    

    public function detailImport($id) 
    {
        $failures = FailuresDetail::where('failures_id', '=', $id)->paginate(config('global.pagination_records'));
        return  $failures;
    }

    function checkData($data, $list, $row)
    {
        $logs = [];
        $result = [];

        if (strlen($data['name']) < config('global.name_length')) {
            $logs['name'] = 'name too short';
        }if (strlen($data['password']) < config('global.password_length')) {
            $logs['password'] = 'password too short';
        }

        foreach($list as $l){
            if($l['name'] == $data['name']){
                $logs['name'] = 'name already taken';
            }
            if($l['email'] == $data['email']) {
                $logs['email'] = 'email already taken';
            }
        }

        $result['row'] = $row;
        $result['log'] = $logs;
        $result['data'] = $data;

        return $result;
       
    }


}