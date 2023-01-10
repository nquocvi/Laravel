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
                                    <li><a href="#">Menu</a></li>
                                    <li class="active">Add</li>
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
                                <strong>Add Category</strong>
                            </div>
                            <div class="card-body card-block">
                                @include('admin.alert')
                                <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="text-input" class=" form-control-label">Category name</label></div>
                                        <div class="col-12 col-md-9"><input type="text" id="text-input" name="name" placeholder="Text" class="form-control"></div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="select" class=" form-control-label">Category parent</label></div>
                                        <div class="col-12 col-md-9">
                                            <select name="parent_id" id="select" class="form-control">
                                                <option value="0"> Select category parent </option>
                                                @foreach($category as $cate)
                                                <option value="{{$cate->id}}">{{$cate->name}}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="textarea-input" class=" form-control-label">Description</label></div>
                                        <div class="col-12 col-md-9"><textarea name="description" id="description" rows="9" placeholder="Content..." class="form-control"></textarea></div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col col-md-3"><label for="textarea-input" class=" form-control-label">Description detail</label></div>
                                        <div class="col-12 col-md-9"><textarea name="description-detail" id="description-detail" rows="9" placeholder="Content..." class="form-control"></textarea></div>
                                    </div>                                 
                                    <div class="row form-group">
                                        <div class="col col-md-3"><label class=" form-control-label">Active</label></div>
                                        <div class="col col-md-9">
                                            <div class="form-check">
                                                <div class="radio">
                                                    <label for="radio1" class="form-check-label ">
                                                        <input type="radio" id="radio1" name="active" value="1" class="form-check-input">Yes
                                                    </label>
                                                </div>
                                                <div class="radio">
                                                    <label for="radio2" class="form-check-label ">
                                                        <input type="radio" id="radio2" name="active" value="0" class="form-check-input">No
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fa fa-dot-circle-o"></i> Submit
                                    </button>
                                    <button type="reset" class="btn btn-danger btn-sm">
                                        <i class="fa fa-ban"></i> Reset
                                    </button>
                                </div>
                                
                            @csrf
                            </form>
                        </div>
                    </div>
            </div>
        </div>
        </div>
    @endsection

    <script>
        ClassicEditor
            .create( document.querySelector( '#description-detail' ) )
            .catch( error => {
                console.error( error );
            } );
    </script>

