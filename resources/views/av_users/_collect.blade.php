@extends('_layouts/'.$layout)
@section('content')
    <div class="row profile">
        <div class="col-md-3 hidden-xs">
            @include('enl_users/sidebar')
        </div>
        <div class="col-md-9">
            <div class="collect-content">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title" style="min-width: 100px"><i class="fa fa-heart"></i> 我的收藏</h3>
                        <div class="box-tools">

                        </div>
                    </div><!-- /.box-header -->
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover collect">
                            <tbody><tr>
                                <th width="5%">ID</th>
                                <th>圖片</th>
                                <th>文章主題</th>
                                <th class="hidden-xs">時間</th>
                                <td width="15%">刪除</td>
                            </tr>
                            @if ($user_collects->count()==0)
                                <tr><td colspan="4" class="text-center">
                                    你尚未收藏任何文章
                                </td></tr>
                            @endif
                            @foreach($user_collects as $k=>$collect)
                            <tr><td>{{$k+1}}</td>

                                <td>
                                    <a href="{{route('articles.show', ['id'=>$collect->ez_map[0]->unique_id,'title'=>hyphenize($title->title)])}}">
                                        @if (File::exists( public_path() . '/focus_photos'."/".$collect->photo) and !empty($collect->photo))
                                            <img src="{!!cdn("focus_photos/100/".$collect->photo) !!}?lastmod={!!$collect->updated_at!!}" class="img-collect img-thumbnail"  alt="{{$collect->title}}">
                                        @else
                                            <img src="{!!cdn("images/nophoto.png") !!}" class="img-collect img-thumbnail"  alt="{{$collect->title}}">
                                    @endif
                                </td>
                                <td ><a
                                            href="{{route('articles.show', ['id'=>$collect->ez_map[0]->unique_id])}}">
                                        @if (mb_strlen(strip_tags($collect->title))<20 )
                                            {!!strip_tags($collect->title)!!}
                                        @else
                                            {!!mb_substr(strip_tags($collect->title),0,19)!!}...
                                        @endif
                                    </a>
                                </td>
                                <td class="hidden-xs">{{$collect->created_at}}</td>
                                <td>
                                    {!!Form::open(['route'=>['enl.user.article.delete'],'method'=>'post','class'=>"form-horizontal"])!!}
                                    <input name="article_id" type="hidden" value="{{$collect->id}}">
                                    <input name="_method" type="hidden" value="DELETE">
                                    <input name="page" type="hidden" value="{!! Input::get('page') !!}">
                                    <button type="submit" class="btn btn-xs glyphicon glyphicon-trash text-muted"></button>
                                    {!!Form::close()!!}
                                </td>

                            </tr>
                            @endforeach

                            </tbody></table>

                    </div><!-- /.box-body -->

                </div>

                <div class="text-center ">
                    {!!show_page_bar_scroll($user_collects,'btn-success btn-lg bg-enl')!!}
                </div>

            </div>
        </div>
    </div>
    <!-- /#wrapper -->
@endsection
@push('scripts')
<script>
var height=$(window).height()-86;
$('.collect-content').css("min-height",height+"px");</script>
@endpush