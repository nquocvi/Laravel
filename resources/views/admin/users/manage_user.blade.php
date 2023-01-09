<!doctype html>
<html class="no-js" lang="">

<head>
    @include('admin.head')
</head>

<body>
    <!-- Left Panel -->
    @include('admin.left_panel')
    <!-- Left Panel -->

    <!-- Right Panel -->
    <div id="right-panel" class="right-panel">

        <!-- Header-->
        @include('admin.right_head')
        <!-- Header-->

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
                                <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <br>
                                    <div class="col-sm-5">
                                        <input type="file" name="file" class="form-control">
                                        <br>
                                        <button class="btn btn-success ">Import User Data</button>
                                    </div>
                                </form>
                                <div class="col-sm-5">
                                    <br>
                                    <a class="btn btn-warning float-end" href="{{ route('users.export') }}">Export User Data</a>
                                </div>
                                <br>
                                <div class="col-sm-12">
                                    <form method="GET">
                                        <div class="input-group mb-3">
                                        <input 
                                            type="text" 
                                            name="search" 
                                            value="{{ request()->get('search') }}" 
                                            class="form-control" 
                                            placeholder="Search..." 
                                            aria-label="Search" 
                                            aria-describedby="button-addon2">
                                        <button class="btn btn-success" type="submit" id="button-addon2">Search</button>
                                        </div>
                                    </form>
                                </div>
                                <hr>
                            </div>
                            <div class="table-stats order-table ov-h">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Select User</th>
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
                                                <form method="post" action="">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input"  name="users[]" id="{{ $user['id'] }}" >
                                                        <label class="custom-control-label" for="{{ $user['id'] }}">{{ $user['id'] }}</label>
                                                    </div>
                                                </form>
                                            </td>
                                            <td>  <span class="name">{{ $user['name'] }}</span> </td>
                                            <td> <span class="product">{{ $user['email'] }}</span> </td>
                                            <td> <span class="product">0{{ $user['phone'] }}</span> </td>
                                            <td><span class="count">{{ $user['role']=='1' ? 'Admin':"User" }}</span></td>
                                            <td>
                                                <a  href="/admin/users/edit-user/{{$user['id']}}">
                                                    <span class="badge badge-complete">Edit</span> 
                                                </a>
                                                <a  href="/admin/users/delete-user/{{$user['id']}}">
                                                    <span class="badge badge-pending" >Delete</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                {!! $users->withQueryString()->links('pagination::bootstrap-5') !!}
                                {{-- <div class="col-sm-5">
                                    <br>
                                    <input type="submit" value="Submit" name="submit">
                                    <a class="btn btn-danger float-end" href="">Delete User Data</a>
                                </div> --}}
                            </div> <!-- /.table-stats -->
                        </div>
                    </div>
            </div>
        </div><!-- .animated -->
    </div><!-- .content -->

    <div class="clearfix"></div>

    <!-- Footer -->
    @include('admin.right_footer')
    <!-- /.site-footer -->
    </div>


    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@2.2.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.4/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-match-height@0.7.2/dist/jquery.matchHeight.min.js"></script>
    <script src="assets/js/main.js"></script>


</body>
</html>
