<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="免费代理IP,代理IP,高匿IP,优质IP,全球免费代理,最新IP"/>
    <meta name="description" content="全球免费代理IP库，高可用IP，精心筛选优质IP，2s必达,每秒持续更新"/>
    <title>每小时热门免费代理IP-高可用全球免费代理IP库</title>
    <link rel="stylesheet" href="/layui/css/layui.css">
    <link rel="stylesheet" href="/css/main.css">
    @include('layout.common_js')
</head>
<body class="lay-blog">
<div class="layui-layout layui-layout-admin">

    @include("layout.header")

    <div class="layui-row ">
        <div class="layui-col-md9">
            <div class="container-wrap">
                <div class="container">
                    <div class="contar-wrap">
                        <h4 class="item-title">
                            <p><i class="layui-icon layui-icon-speaker"></i>公告：<span>每小时更新最热门稳定IP</span></p>
                        </h4>
                        @foreach($blogs as $blog)
                            <div class="item">
                                <div class="item-box  layer-photos-demo1 layer-photos-demo">
                                    <h3><a href="{{ route("blog.detail",['blog_id'=>$blog['id']]) }}">{{ sprintf("%s 全球最新免费HTTP代理IP",date("Y年m月d日H时",strtotime($blog['created_at']))) }}</a></h3>
                                    <h5>发布于：<span>{{ $blog['created_at'] }}</span></h5>
                                    <p> {{ mb_substr($blog['content_html'],0,450) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if( $blogs->currentPage() < $blogs->lastPage())
                        <div class="item-btn">
                            <a class="layui-btn layui-btn-normal" href="{{ $blogs->url($blogs->currentPage() + 1) }}">下一页</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="layui-col-md3 ad-area">
            @include('layout.right_nav')
        </div>
    </div>

</div>

@include("layout.footer")

<script src="/layui/layui.all.js"></script>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/clipboard.js/1.5.16/clipboard.min.js"></script>
<script>
    /* JavaScript代码区域 */
    layui.use('element', function(){
        var element = layui.element;

    });

    $(function () {
        /*广告*/
        var adInterval = setInterval(function () {
            $("#BottomMsg").removeAttr("style");
        }, 100);
        setTimeout(function(){
            clearInterval(adInterval)
        },6000);
    });
</script>
</body>
</html>