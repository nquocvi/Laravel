    @extends('admin.layouts.master')

    @section('breadcrumbs')
        <div class="breadcrumbs">
            <div class="breadcrumbs-inner">
                <div class="row m-0">
                    <div class="col-sm-4">
                        <div class="page-header float-left">
                            <div class="page-title">
                                <h1>Dashboard</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="page-header float-right">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="#">Dashboard</a></li>
                                    <li><a href="#">User</a></li>
                                    <li class="active">List User</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('content')
        <div class="content">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header  col-lg-12">
                                <div class="col-sm-5">
                                <strong class="card-title">List User</strong>
                                </div>
                                <hr>
                                <div class="col-sm-12"> 
                                    <a class="btn btn-success float-end" href="{{ route('users.importUser') }}">Import User Data</a>
                                    <a class="btn btn-warning float-end" href="{{ route('users.export') }}">Export User Data</a>
                                </div>

                                <br>
                                <div class="col-sm-12">
                                    <form method="GET">
                                        <div class="row">
                                            <div class="col-sm-10">
                                                <input 
                                                    type="text" 
                                                    name="search" 
                                                    value="{{ request()->get('search') }}" 
                                                    class="form-control" 
                                                    placeholder="Search..." 
                                                    aria-label="Search" 
                                                    aria-describedby="button-addon2">
                                                    &nbsp &nbsp
                                            </div>
                                            <div class="col-sm-2">
                                                <button class="btn btn-success" type="submit" id="button-addon2"> Search</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <hr>
                            </div>
                            <div class="table-stats order-table ov-h col-sm-12">
                                <form method="post" action="/admin/users/multipleusersdelete">
                                @csrf
                                    
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Role</th>
                                                <th>Manage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($users as $user)
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input"  name="users[]" id="{{ $user['id'] }}" value="{{ $user['id'] }}>" >
                                                        <label class="custom-control-label" for="{{ $user['id'] }}">{{ $user['id'] }}</label>
                                                    </div>
                                                </td>
                                                <td>  <span class="name">{{ $user['name'] }}</span> </td>
                                                <td> <span class="product">{{ $user['email'] }}</span> </td>
                                                <td> <span class="product">0{{ $user['phone'] }}</span> </td>
                                                <td><span class="count">{{ $user['role']=='1' ? 'Admin':"User" }}</span></td>
                                                <td>
                                                    <a  href="/admin/users/edit-user/{{ $user['id'] }}">
                                                        <span class="badge badge-complete">Edit</span> 
                                                    </a>
                                                    <a  href="/admin/users/delete-user/{{ $user['id'] }}">
                                                        <span class="badge badge-pending" >Delete</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                    <div class="col-sm-12"> 
                                        <input class="btn btn-danger" type="submit" name="submit" value="Delete Users"/>
                                        <input class="btn btn-primary" type="submit" name="submit" value="Export Selected"/>
                                    </div>
                                    
                                </form>
                                {!! $users->withQueryString()->links('pagination::bootstrap-5') !!}
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection