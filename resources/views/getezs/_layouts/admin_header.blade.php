{{--logo 含左邊選單---}}
<header class="main-header">
    <!-- Logo -->
    <a href="{{url('/')}}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">Ez</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>Ez</b> New Life</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- Messages: style can be found in dropdown.less-->


                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{asset('images/128.png')}}" class="user-image" alt="User Image">
                            <span class="hidden-xs">
                                @if( Auth::user()->check() )

                                    {{ Auth::user()->get()->name }}
                                @endif</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="{{asset('images/256.png')}}" class="img-circle" alt="User Image">
                            <p>
                                {{ Auth::user()->get()->name }}
                                <small>{{ Auth::user()->get()->created_at }}</small>

                            </p>
                        </li>
                        <!-- Menu Body -->

                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="#" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{url('auth/logout')}}" class="btn btn-default btn-flat">登出</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->

            </ul>
        </div>
    </nav>
</header>

<!-- =============================================== -->

<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{asset('images/128.png')}}" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>@if( Auth::user()->check() )
                        {{ Auth::user()->get()->name }}
                    @endif</p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">MAIN NAVIGATION</li>
            @if (Auth::user()->get()->role=="A")
            <li class="treeview  @if ( strpos(Route::currentRouteName(), 'users')!== false) active @endif">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span>用戶管理</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ URL('admin/users/create') }}"><i class="fa fa-circle-o"></i> 新增用戶</a></li>
                    <li><a href="{{ URL('admin/users') }}"><i class="fa fa-circle-o"></i>用戶列表</a></li>

                </ul>
            </li>
            @endif
            @if (Auth::user()->get()->role=="A")
                <li class="treeview  @if ( strpos(Route::currentRouteName(), 'parameters')!== false) active @endif">
                    <a href="#">
                        <i class="fa  fa-cogs"></i>
                        <span>參數設定</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @foreach(Config::get('app.domain') as $key=>$val )
                            <li><a href="{{ URL('admin/parameters/create?domain='.$val) }}"><i class="fa fa-circle-o"></i>新增{{$key}}參數</a></li>
                            <li><a href="{{ URL('admin/parameters/?domain='.$val) }}"><i class="fa fa-circle-o"></i>{{$key}}參數</a></li>
                        @endforeach

                    </ul>
                </li>
            @endif
            @if (Auth::user()->get()->role=="A")
                <li class="treeview  @if ( strpos(Route::currentRouteName(), 'logs')!== false) active @endif">
                    <a href="#">
                        <i class="fa  fa-hourglass"></i>
                        <span>LOG 查看</span>
                        <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        @foreach(Config::get('app.logs') as $val )
                            <li><a href="{{ URL('admin/logs/?type='.$val) }}"><i class="fa fa-circle-o"></i>{{$val}}</a></li>
                        @endforeach

                    </ul>
                </li>
            @endif

            <li class="treeview  @if ( strpos(Route::currentRouteName(), 'articles')!== false) active @endif">
                <a href="#">
                    <i class="fa fa-file-text"></i>
                    <span>文章管理</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ URL('admin/articles/create') }}"><i class="fa fa-circle-o"></i> 新增文章</a></li>
                    <li><a href="{{ URL('admin/articles/datatables') }}"><i class="fa fa-circle-o"></i>文章列表</a>
                    </li>
                </ul>
            </li>
            <li class="treeview  @if ( strpos(Route::currentRouteName(), 'animations')!== false) active @endif">
                <a href="#">
                    <i class="fa  fa-file-photo-o"></i>
                    <span>動圖管理</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ URL('admin/animations/create') }}"><i class="fa fa-circle-o"></i> 新增動圖</a></li>
                    <li><a href="{{ URL('admin/animations/datatables') }}"><i class="fa fa-circle-o"></i>動圖列表</a>
                    </li>
                    <li><a href="{{ URL('animations') }}" target="_animations"><i class="fa fa-circle-o"></i>動圖預覽</a>
                    </li>
                </ul>
            </li>
            <li  class="treeview  @if ( strpos(Route::currentRouteName(), 'password')!== false or strpos(Route::currentRouteName(), 'self')!== false) active @endif">
                <a href="#"><i class="fa fa-amazon"></i> <span>個人資料管理</span><i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{ URL('admin/users/self') }}"><i class="fa fa-circle-o"></i> 資料資改</a></li>
                    <li><a href="{{ URL('admin/users/password') }}"><i class="fa fa-circle-o"></i>密碼修改</a></li>
                </ul>
            </li>

            <li  class="treeview  @if ( strpos(Route::getCurrentRoute()->getPath(), '/adv/')!== false ) active @endif">
                <a href="#"><i class="fa fa-bullhorn"></i> <span>廣告系統</span><i class="fa fa-angle-left pull-right"></i></a>
                <ul class="treeview-menu">
                    <li><a href="{{ URL('admin/adv/editor') }}"><i class="fa fa-circle-o"></i>修改廣告</a></li>
                    <li><a href="{{ URL('admin/adv/preview') }}"><i class="fa fa-circle-o"></i>廣告預覽</a></li>
                </ul>
            </li>
            <li class="treeview  @if ( strpos(Route::currentRouteName(), 'newsletters')!== false) active @endif">
                <a href="#">
                    <i class="fa fa-envelope"></i>
                    <span>電子報管理</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ URL('admin/newsletters/create') }}"><i class="fa fa-circle-o"></i> 新增電子報</a></li>
                    <li><a href="{{ URL('admin/newsletters/datatables') }}"><i class="fa fa-circle-o"></i>電子報列表</a>
                    </li>
                </ul>
            </li>

            <li class="treeview">
                <a href="#">
                    <i class="fa fa-link"></i> <span>快速連結</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="http://eznewlife.com" target="_blank"><i class="fa fa-circle-o"></i>EzNewLife</a></li>
                    <li><a href="https://www.facebook.com/groups/1470778373168058/"  target="_blank"><i class="fa fa-circle-o"></i>樂為FB社團</a></li>
                    <li><a href="http://gogotest.co/"   target="_blank"><i class="fa fa-circle-o"></i>GoGoTest</a></li>
                    <li><a href="http://vi.gogotest.co/"   target="_blank"><i class="fa fa-circle-o"></i>VI GoGoTest</a></li>
                    <li><a href="http://admin.vdo.watch/"   target="_blank"><i class="fa fa-circle-o"></i>VDO</a></li>
                    <li><a href="{{ URL('admin/logs/content') }}"   target="_log"><i class="fa fa-circle-o"></i>LOG</a></li>

                </ul>
            </li>


        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
