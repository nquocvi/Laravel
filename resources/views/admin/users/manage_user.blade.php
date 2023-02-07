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
                                <h1>ダッシュボード</h1>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="page-header float-right">
                            <div class="page-title">
                                <ol class="breadcrumb text-right">
                                    <li><a href="#">ダッシュボード</a></li>
                                    <li><a href="#">ユーザー</a></li>
                                    <li class="active">ユーザーの一覧表示</li>
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
                                        <div class="form-group col-xs-4">
                                            <br>
                                            <input 
                                                type="text" 
                                                name="search-name" 
                                                value="{{ request()->get('search-name') }}" 
                                                class="form-control" 
                                                placeholder="名前で検索..." 
                                                aria-label="Search" 
                                                aria-describedby="button-addon2">
                                        </div>
                                        <div class="form-group col-xs-4">
                                            <br>
                                            <input 
                                                type="text" 
                                                name="search-email" 
                                                value="{{ request()->get('search-email') }}" 
                                                class="form-control" 
                                                placeholder="メールで検索..." 
                                                aria-label="Search" 
                                                aria-describedby="button-addon2">
                                            <br>
                                        </div>
                                        <div class="form-group col-xs-4">
                                            <br>
                                            <select class="form-control form-select-lg mb-3" name="role" id="cars">
                                                <option value="select">役割で検索...</option>
                                                <option value="{{ config('global.admin_role') }}">管理者</option>
                                                <option value="{{ config('global.user_role') }}">ユーザー</option>
                                            </select>
                                        </div>
                                    </div>
                                    <button class="btn btn-success" type="submit" id="button-addon2"> 検索</button>
                                </form>
                            </div>
                            <hr>
                            <div class="card-header  col-lg-12">
                                <div class="col-sm-5">
                                    <strong class="card-title"><h3>ユーザーの一覧表示</h3></strong>
                                </div>
                                <hr>
                                <div class="col-sm-12"> 
                                    <?php $ids = [] ?>
                                    @foreach($users as $user)
                                        <?php array_push($ids, $user['id']) ?>
                                    @endforeach
                                    <?php $userId = urlencode(base64_encode(json_encode($ids))) ?>
                                    <form method='POST' action="users-export-search" style ='float: right; margin-left: 10px '>
                                        @csrf
                                        <input type="hidden" name="uid" value= {{ $userId }} />
                                        <input class="btn btn-warning" type="submit" name="action" value="Excel をエクスポート"/>
                                    </form>
                                    <form method='POST' action="generate-pdf" target="_blank" style ='float: right; margin-left: 10px'>
                                        @csrf
                                        <input type="hidden" name="uid" value= {{ $userId }} />
                                        <input class="btn btn-warning" type="submit" name="action" value="PDF のエクスポート"/>
                                    </form>
                                    <a class="btn btn-primary" href="{{ route('users.export') }}" style ='float: right; margin-left: 10px'>すべてのユーザー データのエクスポート</a>  
                                    <a class="btn btn-secondary" href="{{ route('users.importUser') }} "style ='float: right;'>ユーザーデータのインポート</a>
                  
                                </div>
                            </div>
                            <div class="table-stats order-table ov-h col-sm-12 table table-bordered">
                                @include('admin.alert')
                                <form method="post" action="/admin/users/multipleusersdelete" >
                                @csrf
                                <br>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <select class  ="form-control form-select-lg mb-3" name="state" id="maxRows">
                                                <option value="5000">すべての行を表示</option>
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
                                                <th class="sortable asc">ユーザーID</th>
                                                <th class="sortable">名前</th>
                                                <th class="sortable">Eメール</th>
                                                <th>電話</th>
                                                <th>役割</th>
                                                <th>管理</th>
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
                                                <td> <span class="name">{{ $user['name'] }}</span> </td>
                                                <td> <span class="product">{{ $user['email'] }}</span> </td>
                                                <td> <span class="product">0{{ $user['phone'] }}</span> </td>
                                                <td> <span class="count">{{ $user['role']=='1' ? '管理者':"ユーザー" }}</span> </td>
                                                <td>
                                                    <a  href="/admin/users/edit-user/{{ $user['id'] }}">
                                                        <span class="badge badge-complete">編集</span> 
                                                    </a>
                                                    <a  href="/admin/users/delete-user/{{ $user['id'] }}">
                                                        <span class="badge badge-pending" >消去</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
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
                                    <div class="col-sm-12" style ='margin-bottom: 10px'> 
                                        <a class="btn btn-danger" href="{{ route('users.deleteUsers') }}"style ='float: right;margin-left: 10px'>すべてのユーザーデータを削除します</a>
                                        <button class="btn btn-danger" type="submit" name="action" value="delete"  style ='float: right; margin-bottom: 10px'>削除</button>
                                        <button class="btn btn-primary" type="submit" name="action" value="export" style ='float: right; margin-right: 10px'>エクスポートの</button>
                                    </div>
                                </form>
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