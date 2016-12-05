@extends('_layouts/admin')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">{{$page['sub_title']}}</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    @if (count($errors) > 0)
                        <div class="row">
                            <div class="alert alert-danger  alert-dismissable col-lg-offset-1 col-lg-8">
                                <h4><i class="icon fa fa-ban"></i> Alert!</h4>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                    <!--auto -save 訊息提示區塊--->
                    <div class="row  direct-chat direct-chat-success"  id="auto-save-block">
                        <div class="direct-chat-msg  col-xs-offset-1 col-lg-5 ">
                            <!-- /.direct-chat-info -->
                            <img src="{{('/images/128.png')}}" class="direct-chat-img" alt="User Image">
                            <div class="direct-chat-text form-status-holder">
                                <a class="btn btn-xs btn-success" href="{!!route('articles.show',$ez_map[0]['unique_id'])!!}" target="_{{$id}}"> &nbsp;<i class="fa  fa-location-arrow"></i> 查看前台文章#{!!$ez_map[0]['unique_id']!!}</a>
                            </div><!-- /.direct-chat-text -->


                        </div>

                    </div>
                    {!!Form::open(['route'=>['admin.articles.auto.update',$id],'method'=>'post','id' => 'article_edit','class'=>"form-horizontal article_form",'files' => true])!!}

                    <input type="hidden" name="id" id="id" value="{{$id}}">
                    <input type="hidden" name="unique_id" id="unique_id" value="{{$ez_map[0]['unique_id']}}">
                     <input type="hidden" name="type" id="type" value="articles">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="category_id" class="col-sm-2 col-lg-1 control-label">分類</label>

                            <div class="col-sm-5 col-lg-3">
                                {!! Form::select('category_id', $categories,(Input::old('category_id')) ? Input::old('category_id') : $category_id,
                                      array('class' => 'form-control','id'=>'category_id','required')) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="category_id" class="col-sm-2 col-lg-1 control-label">Tags</label>

                            <div class="col-sm-10 col-lg-5">
                                {!!Form::text('tags',(Input::old('tags')) ? Input::old('tags') : $db_tags,array('class' => 'form-control','id'=>'tags','placeholder'=>'請輸入tags'))!!}

                            </div>
                        </div>
                        <div class="form-group">
                            <label for="title" class="col-sm-2 col-lg-1 control-label">主題</label>
                            <div class="col-sm-10 col-lg-5">

                                {!!Form::text('title',(Input::old('title')) ? Input::old('title') : $title,array('class' => 'form-control','id'=>'title','placeholder'=>'請輸入文章主題','required'))!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="summary" class="col-sm-2 col-lg-1 control-label">簡述</label>
                            <div class="col-sm-10 col-lg-5">

                                {!!Form::text('summary',(Input::old('summary')) ? Input::old('summary') : $summary,array('class' => 'form-control','id'=>'title','placeholder'=>'請輸入文章簡述','max-length'=>'255'))!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="flag" class="col-sm-2 col-lg-1 control-label">情色</label>
                            <div class="col-sm-10 col-lg-5">
                                {!!Form::checkbox('flag', 'P', (Input::old('flag')=="P" or $flag=="P") ? true: false,['class'=>'minimal-red'])!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="file" class="col-sm-2 col-lg-1 control-label">特色圖片</label>
                            <div class="col-sm-5 col-lg-3">
                                <input type='file' id="imgInp" name="photo" data-placement="right"  data-toggle="tooltip"  data-title="tip:圖片寬至少大於600px，有助於facebook 分享"/>

                                <img id="blah" src="@if (File::exists( public_path() . '/focus_photos'."/400/".$photo) and !empty($photo))
{!!("/focus_photos/400/".$photo) !!}?lastmod={!!$updated_at!!}@else data:image/png;base64,
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
@endif"  class="img-responsive img-thumbnail" alt="特色圖片" style="width:200px;height: 140px" data-toggle="tooltip" data-placement="right" data-title="tip:建議圖片尺寸 640X480 (4*3 比例)"/>
                            </div>
                        </div>

                        <div class="form-group sandbox-container">
                            <label for="publish_date" class="col-sm-2 col-lg-1 control-label">發佈日期</label>
                            <div class="col-sm-4 col-lg-2">

                        {!!Form::text('publish_at',(Input::old('publish_at')) ? Input::old('publish_at') : substr($publish_at,0,16),array('class' => 'form-control','id'=>'publish_at','placeholder'=>'yyyy-mm-dd HH:ii'))!!}

                               <span id="show_now_date" class="btn btn-danger btn-xs" style="float:left">立即發佈</span>
                            </div>
                        </div>

                        <div class="form-group sandbox-container">
                            <label for="content" class="col-sm-2 col-lg-1 control-label">文章內容</label>
                            <div class="col-sm-10 col-lg-10">
                            <!-- 加载编辑器的容器 -->
                            <script id="container" name="content" type="text/plain">
                               {!!(Input::old('content')) ? Input::old('content') : nl2br($content)!!}
                            </script>

                            <!-- 实例化编辑器 -->
                            <script type="text/javascript">
                                var ue = UE.getEditor('container');
                                ue.ready(function() {
                                    ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');//此处为支持laravel5 csrf ,根据实际情况修改,目的就是设置 _token 值.
                                });
                            </script>
                                </div>
                        </div>
                        <div class="box-footer">
                            <button type="reset" class="btn btn-default">Cancel</button>
                            <button type="submit" class="btn btn-primary pull-right">修改文章</button>
                        </div><!-- /.box-footer -->
                        {!! Form::close() !!}
                    </div>

                </div>
            </div></div></section>

@endsection
@push('scripts')
@include('admin.articles.edit_js')
@endpush
