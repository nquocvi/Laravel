<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Services\Admin\UserService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
        $this->userService->handleMultipleUsers($request);
        return back();  
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

    public function importCsv(Request $request) 
    {   
        $this->userService->importCsv($request);
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
