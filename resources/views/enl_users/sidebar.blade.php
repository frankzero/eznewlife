<div class="profile-sidebar">
    <!-- SIDEBAR USERPIC -->
    <div class="profile-userpic">
        <img src="{{Auth::enl_user()->get()->avatar."?".rand()}}" class="img-responsive" alt="">
    </div>
    <!-- END SIDEBAR USERPIC -->
    <!-- SIDEBAR USER TITLE -->
    <div class="profile-usertitle">
        <div class="profile-usertitle-name">
            {{Auth::enl_user()->get()->nick_name}}
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

            <li class="@if (Route::currentRouteName()==='enl.user.profile')active @endif">
                <a href="{{route('enl.user.profile')}}">
                    <i class="glyphicon glyphicon-user"></i>
                    帳號設定 </a>
            </li>
            <li  class="@if (Route::currentRouteName()==='enl.user.collect')active @endif">
                <a href="{{route('enl.user.collect')}}">
                    <i class="glyphicon glyphicon-heart"></i>
                    我的收藏 </a>
            </li>
            <li>
                <a href="{{route('enl.logout')}}">
                    <i class="fa fa-sign-out"></i>
                    登出 </a>
            </li>
        </ul>
    </div>
    <!-- END MENU -->
</div>