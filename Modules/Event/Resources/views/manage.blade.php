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
                                <li class="active">管理</li>
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
                    <strong class="card-title"><h3>{{ $event['name'] }}</h3></strong>
                    @include('admin.alert')
                    <div >
                        <form action="{{ route('event.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-row">
                                <div class="col">
                                    <input type="file" name="file" class="form-control" id ="file-input">
                                    <input type="hidden" id="eventId" name="eventId" value="{{ $event['id'] }}">
                                </div>
                                <div class="col">
                                    <button class="btn btn-success ">Import</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card">
                        <hr>
                        <div class="card-header  col-lg-12">
                            <div class="col-sm-12">
                                <strong class="card-title"><h3>ゲストの一覧表示</h3></strong>
                            </div>
                            <div class="col-sm-2">
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
                            <div class="col-sm-10"> 
                                <a class="btn btn-primary" href="{{ route('event.sendMailInvite',['eventId' => $event['id']]) }}" style ='float: right; margin-left: 10px'>招待メールを送る</a>  
                                <a class="btn btn-secondary" href="{{ route('event.import') }} "style ='float: right;'>ゲストのインポート</a>
              
                            </div>
                        </div>
                        <div class="table-stats order-table ov-h col-sm-12 table table-bordered">
                            <form method="post" action="/admin/events/multipleeventsdelete" >
                            @csrf
                            <br>
                                <table 
                                    class="table table-striped table-class table-hover" id= "table-id">
                                    <thead>
                                        <tr>
                                            <th class="sortable">Select</th>
                                            <th class="sortable">Name</th>
                                            <th class="sortable">Email</th>
                                            <th>Status</th>
                                            <th>Manage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($guests as $guest)
                                        <tr>
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"  name="events[]" id="{{ $guest->user()->get()[0]->id }}" value="{{ $guest->user()->get()[0]->id }}>" >
                                                    <label class="custom-control-label" for="{{ $guest->user()->get()[0]->id }}"></label>
                                                </div>
                                            </td>
                                            <td> <span class="name">{{ $guest->user()->get()[0]->name }}</span> </td>
                                            <td> <span class="product">{{ $guest->user()->get()[0]->email }}</span> </td>
                                            <td> <span class="count">{{ $guest->status == '1' ? 'Not coming':"Da" }}</span> </td>
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