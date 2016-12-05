<div class="widewrapper masthead navbar-fixed-top">
    <div class="container">
        <a href="{{URL('/')}}" id="logo">
            <img src="{{('images/logo.png')}}" alt="logo">
        </a>

        <div class="share-circle-main" style="display: none">

            @if ( Auth::enl_user()->check()===false    )


                <a class="btn btn-success btn-group-xs share-circle "
                           href="#" data-toggle="modal" data-target="#loginModal"
                           target="_blank" alt="收藏">
                    <i class="fa fa-heart-o" data-toggle="tooltip" data-placement="bottom"  title="點我,收藏漫畫" ></i>
                </a>
            @elseif ( isset($collects) and  in_array($article->id,$collects->user_collects->toArray())  )
                <?php $add="hidden";$cancel=""?>
            @else
                <?php $add="";$cancel="hidden"?>
            @endif
            @if ( Auth::enl_user()->check()===true and isset($collects))
                <a href="#"  data-toggle="tooltip" title="點我,取消收藏"
                   class="collect cancel_collect btn  btn-success  btn-group-xs share-circle {{$cancel}} "><i class="fa fa-heart"></i></a>

                <a href="#"  data-toggle="tooltip" title="點我,收藏漫畫"
                   class="collect  add_collect btn btn-success btn-group-xs share-circle  {{$add}} "><i class="fa fa-heart-o"></i></a>
            @endif

        <a class="btn bg-light-blue btn-group-xs share-circle"
           href="http://www.facebook.com/sharer/sharer.php?u={!!rawurldecode($page['share_url'])!!}&description={!!rawurldecode($page['sub_title'])!!}"
           target="_blank" alt="Facebook">
            <i class="fa fa-facebook"></i>
        </a>
        <a class="btn bg-red btn-group-xs share-circle"
           href="https://plus.google.com/share?url={!!rawurldecode($page['share_url'])!!}&t={!!rawurldecode($page['sub_title'])!!}"
           target="_blank">
            <i class="fa fa-google-plus"></i></a>

            <a class="btn bg-aqua share-circle"
               href="https://twitter.com/intent/tweet?url={!!rawurldecode($page['share_url'])!!}&text={!!rawurldecode($page['sub_title'])!!}"
               target="_blank">
                <i class="fa fa-twitter"></i></a>

            <a class="btn share-circle" style="background-color: rgb(210,87,47) ;color:white "
               href="http://www.plurk.com/?qualifier=shares&status={!!rawurldecode($page['share_url'])!!}"
               target="_blank">
                <strong style="font-family: Noto Sans CJK JP Black">P</strong></a>
        </div>
        <div id="mobile-nav-toggle" class="pull-right">
            <a href="#" data-toggle="collapse" data-target=".clean-nav .navbar-collapse">
                <i class="fa fa-bars"></i>
            </a>
        </div>

        <nav class="pull-right clean-nav">

            <div class="collapse navbar-collapse">
                <ul class="nav nav-pills navbar-nav">
                    <li class="dark">

                        <a href="{{route('darks.index')}}" target="_blank">ENL暗黑網</a>
                    </li>
                    @if ($categories)
                        @foreach($categories as $cid=>$name)
                            <li @if ( $page['sub_title'] ==$name ) class=" active" @endif>

                                <a href="{{route('articles.category',[$cid,$name])}}">{{$name}}</a>
                            </li>
                        @endforeach
                    @endif
				
                    @if (Request::server('SERVER_ADDR') == '59.126.180.51')
                        @if( Auth::enl_user()->check() )
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle login" data-toggle="dropdown">
                                    <i class="fa fa-user user"></i></a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{route('enl.user.profile')}}"><i class="fa fa-cog"></i>{{Auth::enl_user()->get()->nick_name}}</a></li>
                                    <li><a href="{{route('enl.user.collect')}}"><i class="fa fa-heart heart"></i>我的收藏</a></li>
                                    <li class="divider"></li>
                                    <li><a href="{{route('enl.logout',[])}}"><i class="fa fa-sign-out"></i> 登出</a></li>
                                </ul>
                            </li>

                        @else

                            <li><a href="{{route('enl.facebook.login')}}" data-toggle="tooltip" title="Facebook 註冊 / 登入"  class="flogin" ><i class="fa fa-facebook user"></i></a>
                           </li>
                        @endif
                    @endif

                </ul>
            </div>
        </nav>



    </div>
</div>