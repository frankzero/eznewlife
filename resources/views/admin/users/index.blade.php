@extends('_layouts/admin')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-8">


                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">用戶管理區</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="user_list" class="table table-bordered table-striped" data-page-length="10" data-order="[[ 0, &quot;asc&quot; ]]">
                            <thead>
                            <tr>
                                <th style="width:10px !important">#</th>
                                <th>帳號</th>
                                <th>身份</th>
                                <th>電子郵件</th>
                                <th>粉絲團</th>
                                <th>更新日期</th>

                                <th style="width:100px">編輯</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($users as $k=> $user)
                            <tr>
                                <td>{{++$k}}</td>

                                <td>{!!$user->name!!}</td>
                                <td>@if ($user->role=="A") <span class="label label-danger">管理員</span> @else <span class="label label-success">小編</span> @endif</td>
                                <td>{!!$user->email!!}</td>
                                <td>@if(isset($user->fb_page) and !empty($user->fb_page)) <a href="https://www.facebook.com/{{$user->fb_page}}" target="_blank">{{$user->fb_page}} </a> @endif</td>
                                <td>{!!$user->created_at!!}</td>
                                <td>

                                    <a href="{{ URL('admin/users/'.$user->id.'/edit') }}" style="color:rgba(8, 4, 4, 0.99)" class="btn btn-default btn-xs pull-left  small-btn" rel="nofollow"
                                       title="修改" data-toggle="tooltip" data-placement="bottom"><i class=" fa  fa-pencil-square-o"></i></a>

                                    @if (!$user->articlesCount->first() and !$user->animationsCount->first())
                                        {!! Form::open(['method' => 'DELETE','route' => ['admin.users.destroy', $user->id],'class'=>'pull-left delete_form']) !!}
                                        {!! Form::hidden('message','你確定要刪除'.$user->name."?") !!}
                                        <button type="submit" class="btn btn-default btn-xs small-btn "  title="刪除" data-toggle="tooltip" data-placement="bottom"><i class='fa fa-trash-o'></i></button>
                                        {!! Form::close() !!}
                                    @endif

                                </td>
                            </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="width:10px !important">#</th>
                                <th>帳號</th>
                                <th>身份</th>
                                <th>電子郵件</th>
                                <th>更新日期</th>
                                <th>粉絲團</th>
                                <th style="width:100px">編輯</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->



@endsection