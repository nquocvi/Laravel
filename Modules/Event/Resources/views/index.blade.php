@extends('event::layouts.master')

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
                                <li><a href="#">イベント</a></li>
                                <li class="active">リスト</li>
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
                                            placeholder="時間で検索......" 
                                            aria-label="Search" 
                                            aria-describedby="button-addon2">
                                        <br>
                                    </div>
                                    <div class="form-group col-xs-4">
                                        <br>
                                        <select class="form-control form-select-lg mb-3" name="role" id="cars">
                                            <option value="select">ステータスで検索...</option>
                                            <option value="{{ config('global.admin_role') }}">Active</option>
                                            <option value="{{ config('global.event_role') }}">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <button class="btn btn-success" type="submit" id="button-addon2"> 検索</button>
                            </form>
                        </div>
                        <hr>
                        <div class="card-header  col-lg-12">
                            <div class="col-sm-12">
                                <strong class="card-title"><h3>リストイベント</h3></strong>
                            </div>
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
                        </div>
                        <div class="table-stats order-table ov-h col-sm-12 table table-bordered">
                            @include('admin.alert')
                            <form method="post" action="/admin/events/multipleeventsdelete" >
                            @csrf
                            <br>
                                <table 
                                    class="table table-striped table-class table-hover" id= "table-id">
                                    <thead>
                                        <tr>
                                            <th class="sortable asc">ID</th>
                                            <th class="sortable">Name</th>
                                            <th class="sortable">Time</th>
                                            <th class="sortable">Start Date</th>
                                            <th class="sortable">End Date</th>
                                            <th>Location</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Manage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($events as $event)
                                        <tr>
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"  name="events[]" id="{{ $event['id'] }}" value="{{ $event['id'] }}>" >
                                                    <label class="custom-control-label" for="{{ $event['id'] }}">{{ $event['id'] }}</label>
                                                </div>
                                            </td>
                                            <td> <span class="name">{{ $event['name'] }}</span> </td>
                                            <td> <span class="product">{{ $event['event_time'] }}</span> </td>
                                            <td> <span class="product">{{ $event['start_date'] }}</span> </td>
                                            <td> <span class="product">{{ $event['end_date'] }}</span> </td>
                                            <td> <span class="product">{{ $event['location'] }}</span> </td>
                                            <td> <span class="product">{{ $event['description'] }}</span> </td>
                                            <td> <span class="count">{{ $event['role']=='1' ? '非活性':"非活性" }}</span> </td>
                                            <td>
                                                <a  href="/admin/event/manage/{{ $event['id'] }}">
                                                    <span class="badge badge-complete">編集</span> 
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