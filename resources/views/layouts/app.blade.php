<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <!-- <link href="/css/app.css" rel="stylesheet"> -->
    <link rel="stylesheet" type="text/css" href="/css/weui.css">
    <link href="/css/public.css" rel="stylesheet" type="text/css">

    @yield('sub-css')

    <!-- Scripts -->
    <script type="text/javascript" src="/js/jquery-1.10.2.min.js"></script>
    <script type="text/javascript" src="/js/global.js"></script>
</head>
</head>

<body>
    <div id="dcWrap">
        <div id="dcHead">
            <div id="head">
                <div class="logo">
                    <a href="{{url('admin')}}"><img src="/img/logo.gif" alt="logo"></a>
                </div>
                <div class="nav">
                    <ul>
                        <li class="M"><a href="JavaScript:void(0);" class="topAdd">新建</a>
                            <div class="drop mTopad"><a href="product.php?rec=add">商品</a> <a href="article.php?rec=add">文章</a> <a href="nav.php?rec=add">自定义导航</a> <a href="show.html">首页幻灯</a> <a href="page.php?rec=add">单页面</a> <a href="manager.php?rec=add">管理员</a>
                                <a href="link.html"></a>
                            </div>
                        </li>
                        <li><a href="/" target="_blank">查看站点</a></li>
                        <li><a href="index.php?rec=clear_cache">清除缓存</a></li>
                        <li><a href="http://www.mycodes.net" target="_blank">帮助</a></li>
                        <li class="noRight"><a href="module.html">DouPHP+</a></li>
                    </ul>
                    <ul class="navRight">
                        <li class="M noLeft"><a href="JavaScript:void(0);">您好，admin</a>
                            <div class="drop mUser">
                                <a href="manager.php?rec=edit&id=1">编辑我的个人资料</a>
                                <a href="manager.php?rec=cloud_account">设置云账户</a>
                            </div>
                        </li>
                        <li class="noRight"><a href="login.php?rec=logout">退出</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- dcHead 结束 -->
        <div id="dcLeft">
            <div id="menu">
                <ul class="top">
                    <li><a href="{{url('admin')}}"><i class="home"></i><em>管理首页</em></a></li>
                </ul>
                <ul>
                    <li><a href="system.html"><i class="system"></i><em>系统设置</em></a></li>
                    <li><a href="show.html"><i class="show"></i><em>首页幻灯广告</em></a></li>
                    <!-- <li><a href="nav.html"><i class="nav"></i><em>自定义导航栏</em></a></li>
                    <li><a href="page.html"><i class="page"></i><em>单页面管理</em></a></li> -->
                </ul>
                <ul>
                    <li><a href="{{url('admin/user')}}"><i class="manager"></i><em>会员管理</em></a></li>
                </ul>
                <ul>
                    <li><a href="{{url('admin/book')}}"><i class="page"></i><em>订单管理</em></a></li>
                </ul>
                <ul>
                    <li><a href="{{url('admin/cate')}}"><i class="productCat"></i><em>菜品分类</em></a></li>
                    <li><a href="{{url('admin/food')}}"><i class="product"></i><em>菜品列表</em></a></li>
                </ul>
                <ul>
                    <li><a href="{{url('admin/table')}}"><i class="productCat"></i><em>餐台管理</em></a></a></li>
                </ul>
                <!-- <ul>
                    <li><a href="article_category.html"><i class="articleCat"></i><em>文章分类</em></a></li>
                    <li><a href="article.html"><i class="article"></i><em>文章列表</em></a></li>
                </ul> -->
                <ul class="bot">
                    <li><a href="backup.html"><i class="backup"></i><em>数据备份</em></a></li>
                    <!-- <li><a href="mobile.html"><i class="mobile"></i><em>手机版</em></a></li> -->
                    <!-- <li><a href="theme.html"><i class="theme"></i><em>设置模板</em></a></li> -->
                    <li><a href="manager.html"><i class="manager"></i><em>网站管理员</em></a></li>
                    <li><a href="manager.php?rec=manager_log"><i class="managerLog"></i><em>操作记录</em></a></li>
                </ul>
            </div>
        </div>
        <div id="dcMain">

            @yield('content')

        </div>
        <div class="clear"></div>
        <div id="dcFooter">
            <div id="footer">
                <div class="line"></div>
                <ul>
                    版权所有 © 2016 菜鸟发布科技有限公司，并保留所有权利。
                </ul>
            </div>
        </div>
        <!-- dcFooter 结束 -->
        <div class="clear"></div>
    </div>

    <!-- Scripts -->
    <!-- <script src="http://www.mycodes.net/js/tongji.js"></script> -->
    <!-- <script src="http://www.mycodes.net/js/youxia.js" type="text/javascript"></script> -->
    <script src="/js/bootstrap.min.js"></script>

    @yield('sub-js')

</body>
</html>
