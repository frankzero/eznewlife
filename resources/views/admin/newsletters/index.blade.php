@extends('_layouts/admin')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">


                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">電子報列表區</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="newsletter_list" class="table table-bordered table-striped" data-page-length="10" data-order="[[ 0, &quot;asc&quot; ]]">
                            <thead>
                            <tr>
                                <th style="width:10px !important">#</th>
                                <th width="40%">標題</th>
                                <th>作者</th>
                                <th>修改者</th>
                                <th>寄送日期</th>
                                <th>更新日期</th>
                                <th style="width:150px">編輯</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($newsletters as $k=> $newsletter)
                                <tr>
                                    <td>{{++$k}}</td>
                                    <td>
                                        @if (File::exists( public_path() . '/focus_photos'."/".$newsletter->photo) and !empty($newsletter->photo))
                                            <a href="#"   data-toggle="popover"  rel="nofollow"  title="{{$newsletter->title}}" data-content='<img src="{!!("/focus_photos/".$newsletter->photo) !!}"  class="img-responsive" width="200px">'/>

                                        @endif
                                        {{$newsletter->title}}

                                        @if (File::exists( public_path() . '/focus_photos'."/".$newsletter->photo) and !empty($newsletter->photo))
                                            </a>
                                        @endif


                                    </td>
                                    <td>{!!$newsletter->author['name']!!}</td>
                                    <td>{!!$newsletter->updater['name']!!}</td>
                                    <td>@if ($newsletter->send_at>0) {!!substr($newsletter->send_at,0,16)!!}@endif</td>

                                    <td>{!!$newsletter->updated_at!!}</td>
                                    <td>
                                            <a href="{{ URL('admin/newsletters/'.$newsletter->id.'/edit') }}" style="color:rgba(8, 4, 4, 0.99)" class="btn btn-default btn-xs" rel="nofollow"
                                               title="修改" data-toggle="tooltip" data-placement="bottom"><i class=" fa  fa-pencil-square-o"></i></a>

&nbsp;

                                            {!! Form::open(['method' => 'DELETE','route' => ['admin.newsletters.destroy', $newsletter->id],'class'=>'pull-left delete_form']) !!}
                                            {!! Form::hidden('message','你確定要刪除'.$newsletter->title."?") !!}
                                            <button type="submit" class="btn btn-default btn-xs small-btn "  title="刪除" data-toggle="tooltip" data-placement="bottom"><i class='fa fa-trash-o'></i></button>
                                            {!! Form::close() !!}


                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr>
                                <th width="10px">#</th>
                                <th width="40%">標題</th>
                                <th>作者</th>
                                <th>修改者</th>
                                <th>寄送日期</th>
                                <th>更新日期</th>
                                <th>編輯</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->



@endsection