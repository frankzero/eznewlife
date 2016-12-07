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
                                <a class="btn btn-xs btn-success" href="{!!route('articles.show',[$ez_map[0]['unique_id'],hyphenize($title)])!!}" target="_{{$id}}"> &nbsp;<i class="fa  fa-location-arrow"></i> 查看admin前台文章#{!!$ez_map[0]['unique_id']!!}</a>
                                <a class="btn btn-xs btn-primary" href="{!!"https://www.facebook.com/sharer/sharer.php?u=".rawurlencode("https://getez.info/".$ez_map[0]['unique_id']."/".hyphenize($title))!!}" target="_{{$id}}"> &nbsp;<i class="fa  fa-facebook"></i> 分享#{!!$ez_map[0]['unique_id']!!}</a>
                                <a class="btn btn-xs btn-danger" href="{!!"https://developers.facebook.com/tools/debug/og/object/?q=".rawurlencode(Config::get('app.master_url')."/".$ez_map[0]['unique_id']."/".hyphenize($title))!!}" target="_{{$id}}"> &nbsp;<i class="fa  fa-bug"></i>除錯#{!!$ez_map[0]['unique_id']!!}</a>
                                <a class="btn btn-xs btn-primary bg-navy" href="{!! route('articles.instant',$ez_map[0]['unique_id']) !!}" target="instant_{{$id}}"> &nbsp;<i class="fa  fa-rocket" ></i>即時文章#{!!$ez_map[0]['unique_id']!!}</a>

                            </div><!-- /.direct-chat-text -->


                        </div>

                    </div>
                    {!!Form::open(['route'=>['admin.articles.auto.update',$id],'method'=>'post','id' => 'article_edit','class'=>"form-horizontal article_form",'files' => true])!!}

                    <input type="hidden" name="id" id="id" value="{{$id}}">
                    <input type="hidden" name="unique_id" id="unique_id" value="{{$ez_map[0]['unique_id']}}">
                     <input type="hidden" name="type" id="type" value="articles">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="url" class="col-sm-2 col-lg-1 control-label">網址</label>
                            <div class="col-sm-10 col-lg-10">
                                <?php $i=0;$protocol="https";?>

                                @foreach (Config::get('app.domain') as $site=>$site_url)
                                    <?php
                                    if ($site == "ENL" or $site=="Getez") $site_title = "/" . hyphenize($title); else $site_title = "";
                                    if ($site == "Dark" ) {
                                        $style = "style='clear:both'" ;

                                    } else{
                                        $style = "";

                                    }
                                            /*
                                    if ($site == "DarkGetez"  or $site=="Dark" or $site=="avbody") {
                                        $protocol="http";
                                    } else {
                                        $protocol="https";
                                    }*/
                                    $i++;
                                    ?>
                                    <div class="col-sm-3 col-lg-1">
                                    <a  class=" copy btn btn-xs btn-success" title="點擊可複製{!! $site !!} 網址"
                                       data-toggle="tooltip" data-clipboard-target="#{{$site}}"><i
                                                class="fa fa-copy"></i>{{$site}}</a>
                                    <a id="{!! $site."_url" !!}" href="{!! $protocol."://".$site_url."/".$map[$i].$site_title !!}" target="_blank"><i class="fa fa-link"></i></a>
                                    </div>
                                    <div class="col-sm-9 col-lg-3">
                                        {!!Form::text($site,$protocol."://".$site_url."/".$map[$i].$site_title,array('class' => 'form-control ','id'=>$site))!!}
                                    </div>



                                @endforeach
                            </div>
                        </div>
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
                            <label for="flag" class="col-sm-2 col-lg-1 control-label">標記</label>
                            <div class="col-sm-10 col-lg-5">
                                {!!Form::checkbox('flag', 'P', (Input::old('flag')=="P" or $flag=="P") ? true: false,['class'=>'minimal-red','id'=>'flag'])!!}<lable class="label label-danger bg-purple">情色</lable>
                                {!!Form::checkbox('instant', '1', (Input::old('instant')=="1" or $instant=="1") ? true: false,['class'=>'minimal-red','id'=>'instant'])!!}<lable class="label label-primary bg-navy">即時文章</lable>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="file" class="col-sm-2 col-lg-1 control-label">特色圖片</label>
								
                            <div class="col-sm-5 col-lg-3">
							
							<div id="article_bar" class="form-group">
                                @if($photo_url!='https://eznewlife.com/focus_photos/')<a class="col-sm-2 col-lg-2  copy btn btn-xs btn-success"  id="btn_photo" title="點擊可複製" data-toggle="tooltip" data-clipboard-target="#photo_url"><i class="fa fa-copy"></i>圖片</a>  <div class="col-sm-10 col-lg-10"> {!!Form::text('photo_url',$photo_url,array('class' => 'form-control','id'=>'photo_url'))!!}</div>@endif
                            </div>


                                <input type='file' id="imgInp" name="photo" data-placement="right"  data-toggle="tooltip"  data-title="tip:圖片寬至少大於600px，有助於facebook 分享"/>

                                <img id="blah" src="@if (File::exists( public_path() . '/focus_photos'."/400/".$photo) and !empty($photo))
{!!("/focus_photos/400/".$photo) !!}?lastmod={!!$updated_at!!}@else {{("/images/no_image.png")}}
@endif"  class="img-responsive img-thumbnail" alt="特色圖片" style="width:200px;height: 140px" data-toggle="tooltip" data-placement="right" data-title="tip:建議圖片尺寸 640X480 (4*3 比例)"/>
                            </div>
                        </div>
					
                        <div class="form-group sandbox-container">
                            <label for="publish_date" class="col-sm-2 col-lg-1 control-label">發佈日期</label>
                            <div class="col-sm-4 col-lg-2">

                        {!!Form::text('publish_at',(Input::old('publish_at')) ? Input::old('publish_at') : substr($publish_at,0,16),array('class' => 'form-control','id'=>'publish_at','placeholder'=>'yyyy-mm-dd HH:ii'))!!}
								@if ($status==0 or $status==2)
                               <span id="show_now_date" class="btn btn-danger btn-xs" style="float:left">立即發佈</span>
							   @endif
                            </div>
                        </div>
						

                        <div class="form-group sandbox-container" >
                            <label for="content" class="col-sm-2 col-lg-1 control-label">文章內容
                                

                            </label>
                            <div class="col-sm-10 col-lg-10" style="z-index:9999;">
     {!!Form::textarea('content',(Input::old('content')) ? Input::old('content') : $content
                     ,array('class' => 'input-block-level',  'rows'    => 18,'id'=>'myeditor',
                     'placeholder'=>'請輸入文章的內容'))!!} 



                            </div>

                        </div><!-- /.box-body -->
                        <!--<div class="box-footer">
                            <button type="reset" class="btn btn-default">Cancel</button>
                            <button type="submit" class="btn btn-primary pull-right">修改文章</button>
                        </div>--><!-- /.box-footer -->
                        {!! Form::close() !!}
                    </div>

                </div>
            </div></div></section>

@endsection
@push('scripts')
@include('admin.articles.edit_js')
@endpush
