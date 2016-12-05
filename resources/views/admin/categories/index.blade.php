@extends('_layouts/admin')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-sm-6">

                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">{{$page['sub_title']}}</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="category_list" class="table table-bordered table-striped" data-page-length="10" data-order="[[ 0, &quot;asc&quot; ]]">
                            <thead>
                            <tr>

                                <th>ID</th>
                                <th>名稱</th>
                                 <th width="50%">說明</th>
                                <th>更新日期</th>

                                <th style="width:100px">編輯</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($categories as $k=> $category)
                            <tr>

                                <td>{!!$category->id!!}</td>

                                <td><a data-toggle="tooltip" data-placement="bottom" title="{!!$category->description!!}" >{!!$category->name!!}</a></td>
                                <td>{!!$category->description!!}</td>
                                <td>@if ($category->updated_at=="-0001-11-30 00:00:00" ){!!$category->created_at!!} @else {!!$category->updated_at!!} @endif</td>
                                <td>

                                    <a href="{{ URL('admin/categories/'.$category->id.'/edit') }}" style="color:rgba(8, 4, 4, 0.99)" class="btn btn-default btn-xs pull-left  small-btn" rel="nofollow"
                                       title="修改" data-toggle="tooltip" data-placement="bottom"><i class=" fa  fa-pencil-square-o"></i></a>


                                </td>
                            </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr>

                                <th>ID</th>
                                <th>名稱</th>
                                <th width="50%">說明</th>
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