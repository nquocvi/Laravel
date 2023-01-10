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
                                    <li class="active">Import</li>
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
                            <div class="card-header">
                                <div class="col-sm-5">
                                   <strong class="card-title">Import User</strong>
                                </div>
                                <hr>
                                @include('admin.alert')
                                <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <br>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <input type="file" name="file" class="form-control" id ="file-input">
                                            </div>
                                            <div class="col-sm-8">
                                                <br>
                                                <button class="btn btn-success ">Import</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <strong class="card-title">Log Table</strong>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">Time</th>
                                            <th scope="col">Total</th>
                                            <th scope="col">Failed</th>
                                            <th scope="col">Erorr details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($failures as $failure)
                                        <tr>
                                            <th scope="row">{{ $failure['created_at'] }}</th>
                                            <td>{{ $failure['total'] }}</td>
                                            <td>{{ $failure['failed'] }}</td>
                                            <td>
                                                <a  href="/admin/users/users-import-detail/{{ $failure['id'] }}">
                                                {{ $failure['detail_failures'] }}
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {!! $failures->withQueryString()->links('pagination::bootstrap-5') !!}
                            </div>
                        </div>
                    </div>
                    @if (session()->has('failures'))
                    <div class="col-lg-12">
                        <strong class="card-title">Log Detail Table</strong>
                        <table class="table table-danger">
                            <tr>
                                <th>Row</th>
                                <th>Attribute</th>
                                <th>Errors</th>
                                <th>Value</th>
                            </tr>

                            @foreach (session()->get('failures')['fail'] as $validation)
                                <tr>
                                    <td>{{ $validation->row() }}</td>
                                    <td>{{ $validation->attribute() }}</td>
                                    <td>
                                        <ul>
                                            @foreach ($validation->errors() as $e)
                                                <li> {{ $e }} </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td>
                                        {{ $validation->values()[$validation->attribute()] }}
                                    </td>
                                </tr>
                                
                            @endforeach
                        </table>
                    </div>
                    @endif
                </div>
            </div><!-- .animated -->
        </div><!-- .content -->
    @endsection