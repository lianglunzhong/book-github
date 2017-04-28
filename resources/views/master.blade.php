<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>@yield('title')</title>
    <link rel="stylesheet" type="text/css" href="/css/app.css"></li>
    <link rel="stylesheet" type="text/css" href="/css/semantic.css"></li>
    <link rel="stylesheet" type="text/css" href="/css/mystyle.css"></li>
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
</head>
<body>
    <div class="ui basic segment" style="margin: 0;">
        <div class="ui container" style="padding-left: 38%;padding-right: 38%;">
            <a href="/" style="cursor: pointer;">
                <img class="ui small centered  image" src="/images/top.jpg">
            </a>
        </div>
    </div>
    <div class="ui basic segment" style="border:1px solid #e5e5e5; margin: 0;padding: 0;">
        <div class="ui container">
            <div class="ui borderless fluid five item menu" style="border:0;box-shadow:0 0 0 0;">
                <a class="item" href="/product/category_id/1">分类1</a>
                <a class="item">分类2</a>
                <a class="item">分类3</a>
                <a class="item">分类4</a>
                <a href="/order_list" class="item">个人中心</a>
            </div>
        </div>
    </div>

    @yield('crumb')

    @yield('content')


    <div class="bk_toptips"><span>注册失败</span></div>
</body>
<script type="text/javascript" src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/app.js"></script>
<script type="text/javascript" src="/js/semantic.min.js"></script>

@yield('my-js')
</html>