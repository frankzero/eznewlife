@extends('_layouts/admin')
@section('content')
<style>		/*this is just to organize the demo checkboxes*/
    label {margin-right:20px;}
    input[type=checkbox] + label {
        color: #ccc;
        font-style: italic;
    }
    input[type=checkbox]:checked + label {
        color: #ae0f18;
        font-style: normal;
        font-weight: bold;
    }
</style>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-lg-8">
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
                        <div class="row  direct-chat direct-chat-success" style="display:none" id="auto-save-block">
                            <div class="direct-chat-msg  col-xs-offset-1 col-lg-5 ">
                                <!-- /.direct-chat-info -->
                                <img src="{{('/images/128.png')}}" class="direct-chat-img" alt="User Image">
                                <div class="direct-chat-text form-status-holder">

                                </div><!-- /.direct-chat-text -->
                            </div>
                        </div>

                        {!!Form::open(['route'=>['admin.fb_lives.store'],'method'=>'post','id' => 'article_form',
                        'class'=>"form-horizontal article_form",'files' => true])!!}
                        <input type="hidden" name="id" id="id" value="{{$id}}">
                        <input type="hidden" name="type" id="type" value="fb_lives">
                        <div class="box-body">
                        <div class="from-group" style="margin-bottom: 50px">
                            <?php $site_url="http://vdo.land";?>
                            <label for="fb_lives_id" class="col-sm-4 col-lg-2 control-label">
                                <a  class=" copy btn btn-xs btn-success" title="點擊可複製FB Live網址"
                                  data-toggle="tooltip" data-clipboard-target="#fb_lives_url"><i
                                            class="fa fa-copy"></i>FB Live</a>
                            </label>
                             <a id="fb_lives_id" style="float:left" href="{!! $site_url."/fb_lives/".$id !!}" target="_blank"><i class="fa fa-link"></i></a>
                            <div class="col-sm-10 col-lg-5">
                                {!!Form::text($site,$site_url."/fb_lives/".$id,array('class' => 'form-control ','id'=>'fb_lives_url'))!!}
                            </div>


                        </div>
                            <div class="form-group">
                                <label for="fb_video" class="col-sm-4 col-lg-2 control-label">FB VIDEO ID</label>
                                <div class="col-sm-10 col-lg-5">

                                    {!!Form::text('fb_video_id',(Input::old('fb_video_id')) ? Input::old('fb_video_id') : $fb_video_id,array('class' => 'form-control','id'=>'fb_video_id','onkeypress'=>'return event.charCode >= 48 && event.charCode <= 57','placeholder'=>'請輸入影片發文ID',''))!!}
                                    <a href="https://www.facebook.com/bookmarks/pages" target="_blank" class="btn btn-primary btn-xs btn-facebook "><i class="fa fa-flag"></i> 我的專頁</a>
                                    <button type="button" class="btn btn-xs btn-default" data-toggle="popover"  data-html="true"   data-original-title="如何取得發文ID"
                                            data-content="<img src='/images/fb-crontab-video.png'>">取得發文ID1</button>&nbsp;
                                    <button type="button" class="btn btn-xs btn-default" data-toggle="popover"  data-html="true"   data-original-title="如何取得發文ID"
                                            data-content="<img src='/images/fb-get-video-id.png'>">取得發文ID2</button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="fb_question" class="col-sm-4 col-lg-2 control-label">FB TOKEN</label>
                                <div class="col-sm-10 col-lg-5">

                                    {!!Form::text('fb_access_token',(Input::old('fb_access_token')) ? Input::old('fb_access_token') : $title,array('class' => 'form-control','id'=>'fb_access_token','placeholder'=>'請輸入你的FB 專頁 TOKEN',''))!!}
                                    <a href="https://developers.facebook.com/tools/explorer/" target="_blank" class="btn btn-primary btn-xs btn-facebook "><i class="fa fa-wrench"></i> 取得 <i class="fa fa-facebbok">access token </i></a>
                                    <button type="button" class="btn btn-xs btn-default" data-toggle="popover"  data-html="true"   data-original-title="如何取得token ID"
                                                                                                                                 data-content="<img src='/images/fb-access-token.png'>">取得tokenID</button>
                                    <button type="button" class="btn btn-xs btn-default" data-toggle="popover"  data-html="true"   data-original-title="如何取得token ID"
                                            data-content="<img src='/images/fb-access-token-public.png'>">記得選擇「公開」</button>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="fb_question" class="col-sm-4 col-lg-2 control-label">問項</label>
                                <div class="col-sm-10 col-lg-5">


                                        {!!Form::text('title',(Input::old('title')) ? Input::old('title') : $title,array('class' => 'form-control','id'=>'title','placeholder'=>'請輸入你的問項',''))!!}

                                </div>
                            </div>
                            <div class="form-group">
                                <label for="fb_answer" class="col-sm-4 col-lg-2 control-label">答</label>
                                <div class="col-sm-10 col-lg-5">
                                    <div class="input-group">
                                        @foreach ($emoji as $k=>$emoji_val)

                                            @if (in_array($emoji_val,$answers))
                                                 <?php $emoji_checked=true;?>
                                            @else
                                                <?php $emoji_checked=false;?>
                                            @endif
                                            {!!  Form::checkbox('answers[]', $emoji_val,$emoji_checked, ['class' => 'css-checkbox','id'=>$emoji_val]) !!}
                                            <label for="{{$emoji_val}}"   class="css-label">
                                                <img src="/images/fbemojis/{{$emoji_val}}.gif" class="flat-red" style="width:35px"> {{$emoji_val}}
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>






                            {!! Form::close() !!}
                        </div>

                </div>
            </div>
    </section>

@endsection
@push('scripts')
<script>
    clipboardDemos=new Clipboard('.copy');
    clipboardDemos.on('success', function(e) {
        e.clearSelection();
        console.log(e);
        console.log(e.id);
        console.info('Action:', e.action);
        console.info('Text:', e.text);
        console.info('Trigger:', e.trigger);
        $(e.trigger).attr('title', '已複製!!').tooltip('fixTitle').tooltip('show');
        $(e.trigger).attr('title', '點擊可複製').tooltip('fixTitle');
        //  $(e.trigger).tooltip('show');
        // var tmp=e.trigger;
        //  console.log(tmp.indexOf("id="));
    });
</script>
@endpush
