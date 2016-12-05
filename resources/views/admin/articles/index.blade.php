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
                        <table id="article_list" class="table table-bordered table-striped" data-page-length="10" data-order="[[ 0, &quot;asc&quot; ]]">
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
                            <tbody>
                            @foreach ($articles as $k=> $article)
                            <tr>
                                <td>{{++$k}}</td>
                                <td>
                                    @if (File::exists( public_path() . '/focus_photos'."/".$article->photo) and !empty($article->photo))
                                        <a href="#"   data-toggle="popover"  rel="nofollow"  title="{{$article->title}}" data-content='<img src="{!!("/focus_photos/".$article->photo) !!}"  class="img-responsive" width="200px">'/>

                                    @endif
                                    {{$article->title}}

                                    @if (File::exists( public_path() . '/focus_photos'."/".$article->photo) and !empty($article->photo))
                                    </a>
                                    @endif


                                </td>
                                <td>{!!$article->category['name']!!}</td>
                                <td>{!!$article->author['name']!!}</td>
                                <td>{!!$article->updater['name']!!}</td>
                                <td>{!!substr($article->publish_at,0,10)!!}</td>

                                <td>{!!$article->updated_at!!}</td>
                                <td> @if ($article->status==1)<a href="#" style=" cursor: not-allowed; color:rgba(8, 4, 4, 0.99)" class="btn btn-default btn-xs pull-left"  rel="nofollow"
                                       title="上架中" data-toggle="tooltip" data-placement="bottom"><i class="  fa fa-toggle-on"></i>
                                       @else
                                            <a href="#" style=" cursor: not-allowed; color:rgba(8, 4, 4, 0.21)" class="btn btn-default btn-xs pull-left" rel="nofollow"
                                               title="未發佈" data-toggle="tooltip" data-placement="bottom"> <i class=" text-gry fa fa-toggle-off"></i>
                                       @endif
                                    </a>
                                    <a href="{{ URL('admin/articles/'.$article->id.'/edit') }}" style="color:rgba(8, 4, 4, 0.99)" class="btn btn-default btn-xs" rel="nofollow"
                                       title="修改" data-toggle="tooltip" data-placement="bottom"><i class=" fa  fa-pencil-square-o"></i></a>
                                            <a href="{!!route("admin.articles.destroy", $article->id)!!}" data-method="delete"
                                            data-token="{!!csrf_token()!!}" data-confirm="Are you sure?"><i class='fa fa-trash'></i></a>

                                            {!! Form::open(['method' => 'DELETE','route' => ['admin.articles.destroy', $article->id],'class'=>'pull-left article_delete_form']) !!}
                                            <button type="submit" class="btn btn-default btn-xs " title="刪除"  data-toggle="tooltip"><i class='fa fa-trash-o'></i></button>
                                             {!! Form::close() !!}

                                </td>
                            </tr>
                            @endforeach

                            </tbody>
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