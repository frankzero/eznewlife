<div class="profile-sidebar">
    <!-- SIDEBAR USERPIC -->
    <div class="profile-userpic">
        <img src=@if(strpos(Auth::av_user()->get()->avatar,"facebook"))"{{Auth::av_user()->get()->avatar."&".rand()}}" @else "{{Auth::av_user()->get()->avatar}}" @endif class="profile-user-img img-responsive img-circle" alt="">
    </div>
    <!-- END SIDEBAR USERPIC -->
    <!-- SIDEBAR USER TITLE -->
    <div class="profile-usertitle">
        <div class="profile-usertitle-name">
            {{Auth::av_user()->get()->nick_name}}
        </div>
        <div class="profile-usertitle-job">

        </div>
    </div>
    <!-- END SIDEBAR USER TITLE -->
    <!-- SIDEBAR BUTTONS -->
    <div class="profile-userbuttons">

    </div>
    <!-- END SIDEBAR BUTTONS -->
    <!-- SIDEBAR MENU -->
    <div class="profile-usermenu">
        <ul class="nav">

            <li class="@if (Route::currentRouteName()==='av.user.profile')active @endif">
                <a href="/me/profile">
                    <i class="glyphicon glyphicon-user"></i>
                    帳號設定 </a>
            </li>
            <li  class="@if (Route::currentRouteName()==='av.user.collect')active @endif">
                <a href="/me/collect">
                    <i class="glyphicon glyphicon-heart"></i>
                    我的收藏 </a>
            </li>
            <li>
                <a href="/auth/logout">
                    <i class="fa fa-sign-out"></i>
                    登出 </a>
            </li>
        </ul>
    </div>
    <!-- END MENU -->
</div>