@extends('_layouts/dark')

@section('content')

    @if ($mobile==true)
        <div class="ad_window_1" id="ad_block_11">
            <?= get_adCode(11, $plan, __DOMAIN__);?>
        </div>
        <div class="modal fade " tabindex="-1" role="dialog" id="ad_block_12">
            <div class="modal-dialog modal-lg">

                <div class="modal-content">

                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body text-center">
                        @if(isset($_GET['ad'])) 手機蓋版廣告 :12 @endif
                        {!! get_adCode(12, $plan, __DOMAIN__) !!}
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    @endif

    
    @if ($mobile==true)
    <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_15" class="adv ">
            @if(isset($_GET['ad'])) 標題內文下方廣告代號 :15 @endif
            
            <?php 
                $gogo = new __gogo($plan, __DOMAIN__);

                $code=$gogo->code();
                
            ?>
            <?=$code;?>
            
    </div>
    @endif


    <article  itemscope itemtype="https://schema.org/Article">


        <div class="body">

            <h1   itemprop="headline name">{{ $article->title }}</h1>
            <span itemprop="author" class="hidden">{{$article->author->name}}</span>
            <h2 class="hidden" itemprop="about">{{$article->description}}</h2>
            <link itemprop="mainEntityOfPage" href="{{https(route('darks.show',$article->ez_map[0]->unique_id))}}?page={!!Input::get('page')?Input::get('page'):1!!}" />
            <time itemprop="dateModified" datetime="{{$article->updated_at_iso}}" class="hidden">{{$article->updated_at_iso}}</time>
            <time itemprop="datePublished" datetime="{{$article->publish_at}}" class="hidden">{{substr($article->publish_at,0,10)}}</time>
                        <span itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
              <span itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
                <meta itemprop="url" content="{{('images/dark_logo.png')}}">
              </span>
              <meta itemprop="name" content="{{ $article->title }}">
            </span>
            <div class="meta">
                <div class="row">
                    <div class="col-xs-7 ">

                        @if ($article->tags->pluck('name')->count()>0)
                            <div class="row tags_show">
                                <div class="col-lg-12" >

                                    @foreach ($article->tags->pluck('name')->all() as $key =>$tag_name)
                                        <a href="/tag/{{$tag_name}}" class="btn btn-xs btn-flat bg-orange">{!!$tag_name!!}</a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                    <div class="col-xs-3">
                        <script src="https://apis.google.com/js/platform.js" async defer></script>
                        <g:plusone></g:plusone>
                    </div>
                    <div class="fb-like col-xs-2" data-layout="button_count"
                         data-href="{!!(https($page['share_url']))!!}"></div>
                </div>

                <div class="row share" id="share" style="margin-top: 15px">
                    <div class="col-xs-3">


                        <a class="btn bg-light-blue btn-block btn-flat"
                           href="http://www.facebook.com/sharer/sharer.php?u={!!rawurldecode(https($page['share_url']))!!}&description={!!rawurldecode($page['sub_title'])!!}"
                           target="_blank" alt="Facebook">
                            <i class="fa fa-facebook " id="fb_share"></i> <span class="share_btn">分享FB</span>
                        </a>
                    </div>
                    <div class="col-xs-3">
                        <a class="btn bg-red btn-block  btn-flat"
                           href="https://plus.google.com/share?url={!!rawurldecode(https($page['share_url']))!!}&t={!!rawurldecode($page['sub_title'])!!}"
                           target="_blank">
                            <i class="fa fa-google-plus"></i><span class="share_btn">分享 G+</span></a>
                    </div>
                    <div class="col-xs-3">
                        <a class="btn bg-aqua btn-block  btn-flat"
                           href="https://twitter.com/intent/tweet?url={!!rawurldecode(https($page['share_url']))!!}&text={!!rawurldecode($page['sub_title'])!!}"
                           target="_blank">
                            <i class="fa fa-twitter"></i><span class="share_btn">分享 Twitter</span></a>
                    </div>
                    <div class="col-xs-3">
                        @if ($mobile==true)
                            <a class="btn btn-block  btn-flat" style="background-color:#1DCD00;color:white "
                               href="http://line.me/R/msg/text/?{!!rawurldecode($page['sub_title'])!!}%0D%0A{!!rawurldecode(https($page['share_url']))!!}"
                               target="_blank">
                                <strong style="font-family: Noto Sans CJK JP Black">L</strong> <span class="share_btn">&nbsp;分享 LINE</span></a>


                        @else
                            <a class="btn btn-block  btn-flat" style="background-color: rgb(210,87,47) ;color:white "
                               href="http://www.plurk.com/?qualifier=shares&status={!!rawurldecode(https($page['share_url']))!!}"
                               target="_blank">
                                <strong style="font-family: Noto Sans CJK JP Black">P</strong> <span class="share_btn">&nbsp;分享 Plurk</span></a>
                        @endif
                    </div>
                </div>

            </div>


            <div class="ad-md">
                <!--手機版 電腦版共用---->
                @if ($mobile==false)
                    <div class="row">

                        <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_3 "
                             class="adv ">
                            @if(isset($_GET['ad'])) 標題內文下方廣告代號 :3 @endif
                            {!! get_adCode(3, $plan, __DOMAIN__) !!}<br></div>
                    </div>
                @endif
                @if ($mobile==true)
                    <div class="row">
                        <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_8 "
                             class="adv ">
                            @if(isset($_GET['ad'])) 標題內文下方廣告代號 :8 @endif
                            {!! get_adCode(8, $plan, __DOMAIN__) !!}<br></div>
                    </div>
                    <p>
                        <!--手機版要秀特色圖片---->
                    <div class="row center">
                        @if (File::exists( public_path() . '/focus_photos'."/".$article->photo) and !empty($article->photo))
                            <img src="{!!("/focus_photos/".$article->photo) !!}?lastmod={!!date("YmdH")!!}"
                                 class="img-responsive img-thumbnail" alt="{{$article->title}}">
                    </div>
                    <div class="row ">
                        <!--標題內文廣告 手機 PC 一樣的---->
                        <div @if(isset($_GET['ad']))  style="background-color: #ffba00" @endif id="ad_block_10"
                             class="adv ">
                            @if(isset($_GET['ad'])) 點我看更多-上方廣告 : 10 @endif
                            {!!  get_adCode(10, $plan, __DOMAIN__) !!}
                        </div>
                    </div>
                    @endif
                            <!---點我看文章 clickme=0 要秀文章 clickme=1 不秀文章 -->
                    <div class="row " @if ($article->content_page>1 or $parameter['clickme']=='0')style="display: none" @endif>
                        <div class="col-lg-4 col-md-3 col-sm-4 col-xs-6 col-centered"><a
                                    class="btn btn-flat btn-danger btn-large disabled btn-block"
                                    id="seemore" onclick="seemore();"
                                    style="background-color: rgb(231, 76, 60);">點我看文章</a></div>
                    </div>

                    @endif
                            <!---點我看文章 clickme=0 要秀文章 clickme=1 不秀文章 -->
                    <div class="article-content" itemprop="articleBody" @if (($mobile==true and $parameter['clickme']=='1')and $article->content_page==1)style="display: none" @endif>

                        {!!$article->content!!}
                                <!--- 文章內頁-->
                        {!!$article->page_bar_scroll!!}
                    </div>
                    @if ($mobile==true)
                        <div class="row ">
                            <!--標題內文廣告 手機 PC 一樣的---->
                            <div @if(isset($_GET['ad']))  style="background-color: #ffba00" @endif id="ad_block_9"
                                 class="adv ">
                                @if(isset($_GET['ad'])) 手機標題下方廣告代號 : 9 @endif
                                {!!  get_adCode(9, $plan, __DOMAIN__) !!}
                            </div>
                        </div>
                    @endif


                    @if ($mobile==false)
                        <div class="row ">

                            <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_4 "
                                 class="adv ">
                                @if(isset($_GET['ad'])) 標題下方廣告代號: 4 @endif
                                {!! get_adCode(4, $plan, __DOMAIN__) !!}<br></div>
                        </div>
        @endif

    </article>



    <aside class="comments" id="comments">

        <article class="fb-comments" data-href="{{https($page['share_url'])}}" data-numposts="5"
                 data-width="100%">
        </article>


    </aside>
    <div class="row center-block ">
        <!--fb 下方廣告 不一樣的---->
        @if ($mobile==true)
            <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_7" class="adv ">
                @if(isset($_GET['ad'])) fb下方廣告代號 : 7 @endif
                {!!  get_adCode(7, $plan, __DOMAIN__) !!}
            </div>
        @else
            <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_5" class=" adv">
                @if(isset($_GET['ad'])) fb下方告代號 : 5 @endif
                {!!  get_adCode(5, $plan, __DOMAIN__) !!}
            </div>
        @endif
    </div>
    <div class="rand-title center-block text-center col-lg-offset-4 "><span
                class="hot-icon glyphicon glyphicon-fire bg-purple"></span> <h4 class="hot-title">最近熱門的文章</h4></div>
    <article class="row">


        @for ($i = 3; $i < 9; $i++)
            @if(isset($rand_articles[$i]))
                <div class=" col-lg-4 col-xs-12 col-sm-6"
                     onclick="window.location='/{{$rand_articles[$i]->ez_map[0]->unique_id}}'">
                    <div class="thumbnail rand_hover "   itemscope itemtype="https://schema.org/ImageObject" >


                        <a href="/{{$rand_articles[$i]->ez_map[0]->unique_id}}">
                            @if (File::exists( public_path() . '/focus_photos'."/".$rand_articles[$i]->photo) and !empty($rand_articles[$i]->photo))
                                <img src="{!!("/focus_photos/400/".$rand_articles[$i]->photo) !!}?lastmod={!!date("YmdH")!!}" itemprop="contentUrl"
                                     class="img-responsive img-thumbnail img-200" alt="{{$rand_articles[$i]->title}}">
                            @else
                                <img src="{!!("/images/nophoto.png") !!}" itemprop="contentUrl"
                                     class="img-responsive img-thumbnail" style="width:200px;height:150px"
                                     alt="{{$rand_articles[$i]->title}}">
                            @endif
                        </a>
                        @if(substr($rand_articles[$i]->publish_at,0,10)!='0000-00-00') <span itemprop="datePublished" class="hidden" content="{!!substr($rand_articles[$i]->publish_at,0,10)!!}"> {!!substr($rand_articles[$i]->publish_at,0,10)!!}</span> @endif
                        <span itemprop="author" class="hidden">{!!$rand_articles[$i]->author->name!!}</span>
                        <span itemprop="description" class="hidden">{!!$rand_articles[$i]->summary!!}</span>
                        <div class="caption hot-caption text-center">
                            <h4>

                                <a
                                        href="/{{$rand_articles[$i]->ez_map[0]->unique_id}}">
                                    <strong itemprop="name">
                                        @if ((strlen(strip_tags($rand_articles[$i]->title))-mb_strlen(strip_tags($rand_articles[$i]->title))<5))
                                            {{---english---}}
                                            @if (strlen(strip_tags($rand_articles[$i]->title))<33)
                                                {!!strip_tags($rand_articles[$i]->title)!!}
                                            @else
                                                {!!substr(strip_tags($rand_articles[$i]->title),0,32)!!}...
                                            @endif

                                        @else
                                            {{---chinese---}}
                                            @if (mb_strlen(strip_tags($rand_articles[$i]->title))<13)
                                                {!!strip_tags($rand_articles[$i]->title)!!}
                                            @else
                                                {!!mb_substr(strip_tags($rand_articles[$i]->title),0,12)!!}...
                                            @endif
                                        @endif
                                    </strong></a>
                            </h4>
                        </div>
                    </div>

                </div>
            @endif
        @endfor
    </article>

@endsection

@section('right_content')
    @if ($mobile==false)

        <div class="alert  alert-dismissible  aside-widget center-block right_ad adv" role="alert">
            <div @if(isset($_GET['ad'])) style="background-color: #ffba00" @endif id="ad_block_6" class="  adv  text-center"
                 style="min-width:100px;min-height:50px;margin:auto;padding:0;" role="alert">
                @if(isset($_GET['ad'])) 300x600廣告代號 : 6 @endif
                {!! get_adCode(6, $plan, __DOMAIN__) !!}
            </div>

            <div class="rand-title row"><i class="rand-icon fa fa-rocket bg-purple"></i> <h4>最新文章</h4></div>

            @for ($i = 0; $i < 2; $i++)
                <div class="row center-block  "
                     onclick="window.location='/{{$rand_articles[$i]->ez_map[0]->unique_id}}'">
                    <div class="thumbnail rand_hover"   itemscope itemtype="https://schema.org/ImageObject">


                        <a href="/{{$rand_articles[$i]->ez_map[0]->unique_id}}">
                            @if (File::exists( public_path() . '/focus_photos'."/".$rand_articles[$i]->photo) and !empty($rand_articles[$i]->photo))
                                <img src="{!!("/focus_photos/400/".$rand_articles[$i]->photo) !!}?lastmod={!!date("YmdH")!!}" itemprop="contentUrl"
                                     class="img-responsive img-thumbnail right-thumbnail" alt="{{$rand_articles[$i]->title}}">
                            @else
                                <img src="{!!("/images/nophoto.png") !!}" itemprop="contentUrl"
                                     class="img-responsive img-thumbnail right-thumbnail" alt="{{$rand_articles[$i]->title}}">
                            @endif
                        </a>
                        @if(substr($rand_articles[$i]->publish_at,0,10)!='0000-00-00') <span itemprop="datePublished" class="hidden" content="{!!substr($rand_articles[$i]->publish_at,0,10)!!}"> {!!substr($rand_articles[$i]->publish_at,0,10)!!}</span> @endif
                        <span itemprop="author" class="hidden">{!!$rand_articles[$i]->author->name!!}</span>
                        <span itemprop="description" class="hidden">{!!$rand_articles[$i]->summary!!}</span>
                        <div class=" caption text-center">
                            <h4>

                                <a
                                        href="/{{$rand_articles[$i]->ez_map[0]->unique_id}}">
                                    <strong itemprop="name">
                                        @if ((strlen(strip_tags($rand_articles[$i]->title))-mb_strlen(strip_tags($rand_articles[$i]->title))<5))
                                            {{---english---}}
                                            @if (strlen(strip_tags($rand_articles[$i]->title))<49)
                                                {!!strip_tags($rand_articles[$i]->title)!!}
                                            @else
                                                {!!substr(strip_tags($rand_articles[$i]->title),0,48)!!}...
                                            @endif

                                        @else
                                            {{---chinese---}}
                                            @if (mb_strlen(strip_tags($rand_articles[$i]->title))<18)
                                                {!!strip_tags($rand_articles[$i]->title)!!}
                                            @else
                                                {!!mb_substr(strip_tags($rand_articles[$i]->title),0,17)!!}...
                                            @endif
                                        @endif
                                    </strong></a>
                            </h4>
                        </div>
                    </div>
                    <div>
                        @endfor
                    </div>
                    @endif



                    @endsection


@push('scripts')
<script>
    @if ($mobile==true)
    // $('#ad_block_12').modal('show')
    @endif
    var body = document.body,
            html = document.documentElement;

    var height = Math.max(body.scrollHeight, body.offsetHeight,
            html.clientHeight, html.scrollHeight, html.offsetHeight);
    var fb_like = 0;

    $(window).scroll(function () {

        if ($(this).scrollTop() > $("#share").offset().top) {
            $(".blockblock").fadeIn();

            $(".logo").attr("src", "{{("images/dark_logo_m.png")}}");
        } else {
            $(".blockblock").fadeOut();
            $(".logo").attr("src", "{{("images/dark_logo.png")}}");
        }

    });
    @if ($mobile==true  and $article->content_page==1)
        var ffb ={!!(strpos(URL::previous(), 'facebook.com')>0 )? '1':'0'!!};
        //var ffb=true;
        console.log('ffb='+ffb)
        function seemore() {

            $('.article-content').css('display', 'block');
            $('#seemore').css('display', 'none');
            // gogo();
        }
        function openbutton() {
            onloaded = true;
            //wait 4 s
            $("#seemore").removeClass("disabled");
            //0.5 s Action
            //setTimeout(gogo, 500);
            gogo(ffb);
        }


        function ad_show(){
            <?php
             //get_adCode(12, $plan, __DOMAIN__);
            ?>
            @if ($add->code!='<div style="text-align:center;">廣告</div>')
                 //$('#ad_block_12').modal('show');
                 console.log('ad has');
            @else
                console.log('ad null');
            @endif
        }

        function gogo(ffb){

            var rffb='<?=fconfig('rffb');?>'-0;
            var delayffb='<?=fconfig('delayffb');?>'-0;
            var ffrp = '<?=fconfig('ffrp');?>';
            var ad_window_time = '<?=fconfig('ad_window_time');?>';
            var ffb_time = '<?=fconfig('ffb_time');?>';

            var go = new __gogo(ffb, rffb, ffb_time, ad_window_time);
            console.log("para="+ffb+','+rffb+','+ ffb_time+','+ ad_window_time);

            go.delay(delayffb);

            go.load(ffrp);

            go.execute();
        }

        //按鈕等待時間
        var delaybuttonshow='<?=fconfig('delaybuttonshow');?>'-0;
        setTimeout(openbutton, delaybuttonshow);


    @endif
</SCRIPT>
@endpush