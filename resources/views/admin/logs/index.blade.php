@extends('_layouts/admin')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">{{$page['sub_title']}}</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="parameter_list" class="table table-bordered table-striped" data-page-length="10" data-order="[[ 0, &quot;asc&quot; ]]">
                            <thead>
                            <tr>
                                <th style="width:10px !important">#</th>
                                <th>值</th>
                                <th>錯誤碼</th>
                                <th>更新日期</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($logs as $k=> $log)
                                <tr>
                                    <td>{{++$k}}</td>


                                    <td><a href="{{route('articles.show',$log->data)}}" target="_blank">{!!$log->data!!}</a></td>
                                    <td>{!!$log->description!!}</td>
                                    <td>@if ($log->updated_at=="-0001-11-30 00:00:00" ){!!$log->created_at!!} @else {!!$log->updated_at!!} @endif</td>

                                </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr>
                                <th style="width:10px !important">#</th>
                                <th>值</th>
                                <th>錯誤碼</th>
                                <th>更新日期</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div><!-- /.box-body -->
                </div><!-- /.box -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </section><!-- /.content -->


    {!!$logs->render()!!}
@endsection