    @extends('admin.layouts.master')

    @section('css-style')
        <style>
            body{
                background-color: #eee; 
            }

            table th , table td{
                text-align: center;
            }

            .pagination li:hover{
                cursor: pointer;
            }
            table tbody tr {
                display: none;
            }

            th.sortable {
                position: relative;
                cursor: pointer;
            }

            th.sortable::after {
                font-family: FontAwesome;
                content: "\f0dc";
                position: absolute;
                right: 8px;
                color: #999;
            }

            th.sortable.asc::after {
                content: "\f0d8";
            }

            th.sortable.desc::after {
                content: "\f0d7";
            }

            th.sortable:hover::after {
                color: #333;
            }
        </style>
    @endsection

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
                            <div class="col-sm-12">
                                <form method="GET">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <br>
                                            <input 
                                                type="text" 
                                                name="search-name" 
                                                value="{{ request()->get('search-name') }}" 
                                                class="form-control" 
                                                placeholder="Search with name..." 
                                                aria-label="Search" 
                                                aria-describedby="button-addon2">
                                                &nbsp &nbsp
                                            <input 
                                                type="text" 
                                                name="search-email" 
                                                value="{{ request()->get('search-email') }}" 
                                                class="form-control" 
                                                placeholder="Search with email..." 
                                                aria-label="Search" 
                                                aria-describedby="button-addon2">
                                            <br>
                                            <div class="form-group">
                                                <select class="form-control form-select-lg mb-3" name="role" id="cars">
                                                    <option value="select">Search with Role...</option>
                                                    <option value="{{ config('global.admin_role') }}">Admin</option>
                                                    <option value="{{ config('global.user_role') }}">User</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-success" type="submit" id="button-addon2"> Search</button>
                                </form>
                            </div>
                            <hr>
                            <div class="card-header  col-lg-12">
                                <div class="col-sm-5">
                                    <strong class="card-title"><h3>List User</h3></strong>
                                </div>
                                <hr>
                                <div class="col-sm-12"> 
                                    <a class="btn btn-secondary float-end " href="{{ route('users.importUser') }}">Import User Data</a>
                                    <a class="btn btn-secondary float-end " href="{{ route('users.export') }}">Export User Data</a>
                                </div>
                            </div>
                            <div class="table-stats order-table ov-h col-sm-12 table table-bordered">
                                @include('admin.alert')
                                <form method="post" action="/admin/users/multipleusersdelete">
                                @csrf
                                <br>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <select class  ="form-control form-select-lg mb-3" name="state" id="maxRows">
                                                <option value="5000">Show ALL Rows</option>
                                                <option value="5">5</option>
                                                <option value="10">10</option>
                                                <option value="15">15</option>
                                                <option value="20">20</option>
                                                <option value="50">50</option>
                                                <option value="70">70</option>
                                                <option value="100">100</option>
                                               </select>
                                         </div>
                                    </div>
                                    <table 
                                        class="table table-striped table-class table-hover" id= "table-id">
                                        <thead>
                                            <tr>
                                                <th class="sortable asc">User ID</th>
                                                <th class="sortable">Name</th>
                                                <th class="sortable">Email</th>
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
                                    <br>
                                    <div class="col-sm-12"> 
                                        <input class="btn btn-danger" type="submit" name="action" value="Delete Selected"/>
                                        <input class="btn btn-primary" type="submit" name="action" value="Export Selected"/>
                                    </div>
                                </form>
                                <div class="col-sm-12"> 
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination">
                                            <li data-page="prev" class="page-item" >
                                                <span>
                                                    < 
                                                    <span class="sr-only">(current)</span>
                                                </span>
                                            </li>
                                            <li data-page="next" id="prev" class="page-item">
                                                <span>
                                                    > 
                                                    <span class="sr-only">(current)</span>
                                                </span>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('js-script')
        @include('admin.partials.pagination_table')
    @endsection