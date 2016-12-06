@extends('_layouts/admin')
@section('content')
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
                            <?php $site_url="https://getez.info";?>
                            <label for="fb_lives_id" class="col-sm-4 col-lg-2 control-label">
                                <a  class=" copy btn btn-xs btn-success" title="點擊可複製FB Live網址"
                                  data-toggle="tooltip" data-clipboard-target="#fb_lives_url"><i
                                            class="fa fa-copy"></i>FB Live</a>
                            </label>
                             <a id="fb_lives_id" style="float:left" href="{!! $site_url."/fb_live/".$id !!}" target="_blank"><i class="fa fa-link"></i></a>
                            <div class="col-sm-10 col-lg-5">
                                {!!Form::text($site,$site_url."/fb_live/".$id,array('class' => 'form-control ','id'=>'fb_lives_url'))!!}
                            </div>

                        </div>
                            <div class="form-group">
                                <label for="fb_video" class="col-sm-4 col-lg-2 control-label">FB VIDEO ID</label>
                                <div class="col-sm-10 col-lg-5">

                                    {!!Form::text('fb_video_id',(Input::old('fb_video_id')) ? Input::old('fb_video_id') : $title,array('class' => 'form-control','id'=>'title','placeholder'=>'請輸入影片發文ID',''))!!}
                                    <button type="button" class="btn btn-xs btn-danger" data-toggle="popover"  data-html="true"   data-original-title="如何取得發文ID"
                                            data-content="發文<code>video id</code>，一定要取得，否則直播數量會無法顯示<p><img src='/images/fb-crontab-video.png'>">Step1</button>&nbsp;
                                    <button type="button" class="btn btn-xs btn-danger" data-toggle="popover"  data-html="true"   data-original-title="如何取得發文ID"
                                            data-content="發文<code>video id</code>，一定要取得，否則直播數量會無法顯示<p><img src='/images/fb-get-video-id.png'>">Step2</button>                               </div>
                            </div>
                            <div class="form-group">
                                <label for="fb_video" class="col-sm-4 col-lg-2 control-label">問與答</label>
                                <div class="col-sm-10 col-lg-5">
                                    <div class="input-group ">
                                        <span class="input-group-addon input-question-label"><i class="fa fa-question"></i></span>
                                        <input type="text"  name="title" class="form-control input-question" placeholder="請輸入你的問項">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="fb_video" class="col-sm-4 col-lg-2 control-label"></label>
                                <div class="col-sm-10 col-lg-5">
                                    <div class="input-group">
                                        <span class="input-group-addon"><img src="/images/fbemojis/like.gif" class="" style="width:25px"></span>
                                        <input type="text"  name="like-txt" class="form-control input-answers" placeholder="請輸入你的答項">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="fb_video" class="col-sm-4 col-lg-2 control-label"></label>
                                <div class="col-sm-10 col-lg-5">
                                    <div class="input-group">
                                        <span class="input-group-addon"><img src="/images/fbemojis/love.gif" class="" style="width:25px"></span>
                                        <input type="text"  name="love-txt" class="form-control input-answers" placeholder="請輸入你的答項">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="fb_video" class="col-sm-4 col-lg-2 control-label"></label>
                                <div class="col-sm-10 col-lg-5">
                                    <div class="input-group">
                                        <span class="input-group-addon"><img src="/images/fbemojis/haha.gif" class="" style="width:25px"></span>
                                        <input type="text"  name="haha-txt" class="form-control input-answers" placeholder="請輸入你的答項">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="fb_video" class="col-sm-4 col-lg-2 control-label"></label>
                                <div class="col-sm-10 col-lg-5">
                                    <div class="input-group">
                                        <span class="input-group-addon"><img src="/images/fbemojis/shock.gif" class="" style="width:25px"></span>
                                        <input type="text"  name="shock-txt" class="form-control input-answers" placeholder="請輸入你的答項">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="fb_video" class="col-sm-4 col-lg-2 control-label"></label>
                                <div class="col-sm-10 col-lg-5">
                                    <div class="input-group">
                                        <span class="input-group-addon"><img src="/images/fbemojis/sad.gif" class="" style="width:25px"></span>
                                        <input type="text"  name="sad-txt" class="form-control input-answers" placeholder="請輸入你的答項">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="fb_video" class="col-sm-4 col-lg-2 control-label"></label>
                                <div class="col-sm-10 col-lg-5">
                                    <div class="input-group">
                                        <span class="input-group-addon"><img src="/images/fbemojis/angry.gif" class="" style="width:25px"></span>
                                        <input type="text"  name="angry-txt" class="form-control input-answers" placeholder="請輸入你的答項">
                                    </div>
                                </div>
                            </div>




                            <div class="box-footer">
                                <button type="submit" name="send" class="btn btn-primary pull-right loose"><i class="fa fa-send"></i> 立即寄送</button> &nbsp;&nbsp;
                                <button type="submit" name="edit" class="btn btn-success pull-right loose">修改電子報</button>
                            </div><!-- /.box-footer -->
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
