<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">切换菜单</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                博飞科技
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
                <li class="{{ active_class(if_route('jd')) }}"><a href="{{route('jd')}}">京东评论</a></li>
                <li class="{{ active_class(if_route('tmallproduct.index')) }}"><a href="{{route('tmallproduct.index')}}">天猫收藏</a></li>
                <li class="{{ active_class(if_route('shoutao.index')) }}"><a href="{{route('shoutao.index')}}">手淘排名</a></li>
                <li class="{{ active_class(if_route('productanalys.index')) }}"><a href="{{route('productanalys.index')}}">产品分析</a></li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                <li><a href="#">登录</a></li>
                <li><a href="#">注册</a></li>
            </ul>
        </div>
    </div>
</nav>