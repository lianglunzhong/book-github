@extends('master')

@section('title', $category->name)

@section('crumb')

<div class="ui segment" style="border:0;box-shadow: 0 0 0 0;">
    <div class="ui container">
        <div class="ui small breadcrumb" style="background-color: #fff;">
            <a class="section" href="/">首页</a>
            <div class="divider"> / </div>
            <a class="active section" href="javascript:void();" style="cursor: default;">{{ $category->name }}</a>
        </div>
        <div class="ui divider" style="margin-top: 1px;"></div>
    </div>
</div>

<div class="ui segment" style="border:0;box-shadow: 0 0 0 0;">
    <div class="ui container">
        <div class="ui cards">
        @foreach ($products as $product )
            <div class="card" style="width: 28%;margin:15px 1.5%;box-shadow: 0 0 0 0;">
                <div class="image" style="background-color: #fff;">
                    <a href="/product/{{ $product->id }}">
                        <img src="{{ $product->preview }}" style="width: 60%;">
                    </a>
                </div>
                <div class="content" style="border-top: none;padding-bottom: 5px;padding-left: 0px;">
                    <div class="header">{{ $product->name }}</div>
                    <div class="meta">
                        <label style="color: red;">${{ $product->price }}</label>
                    </div>
                    <div class="description" style="margin-top: 0px;">
                        {{ $product->summary }}
                    </div>
                </div>
                <div class="extra content" style="padding-top: 2px;padding-left: 0;padding-right: 15px;">
                    <a class="right floated created">
                        <i class="shop icon"></i>加入购物车
                    </a>
                    <a class="like product-like">
                        <i class="like icon"></i> <span>4</span> 喜欢
                    </a>
                </div>
            </div>
        @endforeach
        </div>
        <!--分页-->
        <div class="ui right floated basic segment">
            <div class="M-box2"></div>
        </div>
    </div>
</div>

@endsection

@section('content')

@endsection


@section('my-js')
<script type="text/javascript">
    $('.product-like').click(function(){
        var num = $(this).children('span').html();
        var liked = $(this).find('i').attr('class');

        if(liked.indexOf('add-like') != -1) {
            num = parseInt(num) - 1;
            $(this).children('span').html(num);
            $(this).find('i').removeClass('add-like');
        } else {
            num = parseInt(num) + 1;
            $(this).children('span').html(num);
            $(this).find('i').addClass('add-like');
        }
    })
</script>

<link rel="stylesheet" type="text/css" href="/css/pagination.css"></li>
<script type="text/javascript" src="/js/jquery.pagination.js"></script>
<script type="text/javascript">
    //分页
    $('.M-box2').pagination({
        coping:true,
        homePage:'首页',
        endPage:'末页',
        //上/下一页节点内容
        prevContent:'<',
        nextContent:'>',
        //总页数
        pageCount:20,
        //当前选中页前后页数
        count:2,
        //当前第几页
        current:{{ $page }},
        //回调函数
        callback: function(api) {
            //获取当前页
            var current_page = api.getCurrent();
            var category_id = '{{ $category->id }}';
            window.location.href = '/product/category_id/'+category_id+'?page='+current_page;
        },
    });

</script>

@endsection