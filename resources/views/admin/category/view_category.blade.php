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
                                    <li><a href="#">Category</a></li>
                                    <li class="active">List Category</li>
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
                                <strong class="card-title">List Category</strong>
                            </div>
                            <div class="table-stats order-table ov-h">
                                <table class="table ">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <!-- <th>Description</th> -->
                                            <th>Status</th>
                                            <th>Manage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($categories as $category)
                                        <tr>
                                            <td class="serial">{{$category->id}}</td>
                                            <td>  <span class="name">{{$category->name}}</span> </td>
                                            <!-- <td> <span class="product">{{$category->description}}</span> </td> -->
                                            <td><span class="count">{{$category->active == '1' ? 'Active':"Inactive"}}</span></td>
                                            <td>
                                                
                                                <a  href="#">
                                                    <span class="ti-pencil-alt"></span> Edit</span>
                                                </a>
                                                <span></span>|</span>
                                                <a  href="#">
                                                <span class="ti-trash"></span> Delete</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div> <!-- /.table-stats -->
                        </div>
                    </div>
                </div>
            </div><!-- .animated -->
        </div><!-- .content -->
    @endsection

