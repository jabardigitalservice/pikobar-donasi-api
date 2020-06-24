<header class="main-header">
    <!-- Logo -->
    <a href="{{route('backend::home')}}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>P</b>D</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>{{ $app_site_title }}</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">{{$app_site_title}} <span
                                class="caret"></span></a>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
        @auth
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="hidden-xs"></span>Profile
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img id="dropdown-avatar" width="256px" height="256px"
                                     class="profile-user-img img-responsive img-circle"
                                        {!! auth()->user()->avatar ? ' src="'.asset(Storage::url(auth()->user()->image->image_url)).'"'
                                        : ' data-src="holder.js/128x128?text=128x128"' !!}>
                                <p>
                                    <small>{{ auth()->user()->nick_name }}</small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="javascript:;"
                                       class="btn btn-default btn-flat"><i class="fa fa-user">&nbsp;Profile</i></a>
                                </div>
                                <div class="pull-right">
                                    <form action="{{route('logout')}}" method="post" style="text-align: center">
                                        @csrf
                                        <input type="submit" value="Sign Out" class="btn btn-default btn-flat"/>
                                    </form>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        @endauth
    </nav>
</header>