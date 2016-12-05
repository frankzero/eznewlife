@extends('_layouts/admin')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">


                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">小編文章列表區</h3>
                    </div><!-- /.box-header -->
                    <div class="box-body">
                        <table id="article_server" class="table table-bordered table-striped" data-page-length="10" data-order="[[ 0, &quot;asc&quot; ]]">
                            <thead>
                            <tr>
                                <th style="width:10px !important">#</th>
                                <th width="40%">標題</th>
                                <th>類別</th>
                                <th>作者</th>
                                <th>修改者</th>
                                <th>發佈日期</th>
                                <th>更新日期</th>
                                <th style="width:150px">編輯</th>
                            </tr>
                            </thead>

                            <tfoot>
                            <tr>
                                <th width="10px">#</th>
                                <th width="40%">標題</th>
                                <th>類別</th>
                                <th>作者</th>
                                <th>修改者</th>
                                <th>發佈日期</th>
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