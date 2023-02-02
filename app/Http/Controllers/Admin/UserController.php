<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Services\Admin\UserService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getUsers(Request $request)
    {
        return view('admin.users.manage_user',[
            'title' => 'Manage User',
            'users'  => $this->userService->getUsers($request),
        ]); 
    }

    public function getUser($id)
    {
        return view('admin.users.edit_user',[
            'title' => 'Edit User',
            'user' => $this->userService->getUser($id),
        ]);
    }

    public function deleteUser($id)
    {
        $this->userService->deleteUser($id);
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
                        }
                    }
                    Session::flash('success','Deleted!');
                    break;

                case 'Export Selected':
                    $ids = array_map('intval', $id);
                    $usersExport = new UsersExport($ids);
                    return Excel::download($usersExport, 'users.xlsx');
                    break;
            }
            return back();
        }else{
            Session::flash('error','Select user please!');
            return back();
        }
       
    }

    public function updateUser($id, Request $request)
    {
        return view('admin.users.edit_user',[
            'title' => 'Edit User',
            'user' => $this->userService->updateUser($id, $request),
        ]);
    }

    public function importUser() 
    {
        return view('admin.users.import_user',[
            'title' => 'Import User',
            'failures' => $this->userService->importUser(),
        ]);
    }

    public function export() 
    {
        $usersExport = new UsersExport();
        return Excel::download($usersExport, 'users.xlsx');
    }

    public function exportSearch(Request $request) 
    {
        $ids = (json_decode(base64_decode(urldecode($request->uid))));
        $usersExport = new UsersExport($ids);
        return Excel::download($usersExport, 'users.xlsx');
    }

    public function importCsv(Request $request) 
    {   
        $this->userService->importCsv($request);
        return back();
    }

    public function importCsvBatch(Request $request) 
    {   
        $this->userService->importCsvBatch($request);
        return back();

    }

    public function detailImport($id) 
    {
        return view('admin.users.import_detail',[
            'title' => 'Detail Import',
            'failures' =>  $this->userService->detailImport($id),
        ]);
    }
}
