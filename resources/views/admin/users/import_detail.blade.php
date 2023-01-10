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
                                <li >Import</li>
                                <li class="active">Detail</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('content')
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <strong class="card-title">Log Detail Table</strong>
                    </div>
                    <div class="card-body">
                        <table class="table table-danger">
                            <tr>
                                <th>Line</th>
                                <th>Attribute</th>
                                <th>Errors</th>
                                <th>Value</th>
                            </tr>
        
                            @foreach ($failures as $validation)
                                <tr>
                                    <td>{{ $validation['line'] }}</td>
                                    <td>{{ $validation['attribute'] }}</td>
                                    <td>{{ $validation['erorr'] }}</td>
                                    <td> {{ $validation['value'] }}</td>
                                </tr>
                            @endforeach
                        </table>
                        {!! $failures->withQueryString()->links('pagination::bootstrap-5') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection