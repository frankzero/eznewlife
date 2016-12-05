@extends('_layouts/admin')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-9">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">{{$page['sub_title']}}</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="parameter_list" class="table table-bordered table-striped" data-page-length="10" data-order="[[ 0, &quot;asc&quot; ]]">
                            <thead>
                            <tr>
                                <th style="width:10px !important">#</th>
                                <th>參數名稱</th>
                                <th>值</th>
                                <th width="50%">參數說明</th>
                                <th>更新日期</th>

                                <th style="width:100px">編輯</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($parameters as $k=> $parameter)
                            <tr>
                                <td>{{++$k}}</td>

                                <td><a data-toggle="tooltip" data-placement="bottom" title="{!!$parameter->description!!}" >{!!$parameter->name!!}</a></td>
                                <td>{!!$parameter->data!!}</td>
                                <td>{!!$parameter->description!!}</td>
                                <td>@if ($parameter->updated_at=="-0001-11-30 00:00:00" ){!!$parameter->created_at!!} @else {!!$parameter->updated_at!!} @endif</td>
                                <td>

                                    <a href="{{ URL('admin/parameters/'.$parameter->id.'/edit') }}" style="color:rgba(8, 4, 4, 0.99)" class="btn btn-default btn-xs pull-left  small-btn" rel="nofollow"
                                       title="修改" data-toggle="tooltip" data-placement="bottom"><i class=" fa  fa-pencil-square-o"></i></a>

                                        {!! Form::open(['method' => 'DELETE','route' => ['admin.parameters.destroy', $parameter->id],'class'=>'pull-left delete_form']) !!}
                                        {!! Form::hidden('message','你確定要刪除'.$parameter->name."?") !!}
                                        <button type="submit" class="btn btn-default btn-xs small-btn "  title="刪除" data-toggle="tooltip" data-placement="bottom"><i class='fa fa-trash-o'></i></button>
                                        {!! Form::close() !!}

                                </td>
                            </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="width:10px !important">#</th>
                                <th>參數名稱</th>
                                <th>值</th>
                                <th width="50%">參數說明</th>
                                <th>更新日期</th>
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