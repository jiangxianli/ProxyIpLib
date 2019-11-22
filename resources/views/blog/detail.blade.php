<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="免费代理IP,代理IP,高匿IP,优质IP,全球免费代理"/>
    <meta name="description" content="全球免费代理IP库，高可用IP，精心筛选优质IP,5s必达"/>
    <title>{{ sprintf("%s 全球最新免费HTTP代理IP",date("Y年m月d日H时",strtotime($blog['created_at']))) }}- 高可用全球免费代理IP库</title>
    <link rel="stylesheet" href="/layui/css/layui.css">
    <link rel="stylesheet" href="/css/main.css">
    <style>
        .content-footer {
            background: #eee;
            height: 44px;
            line-height: 44px;
            padding: 0 15px;;
        }
        .layui-logo a{
            color: #009688;
        }
    </style>

    @include('layout.common_js')
</head>
<body class="lay-blog">
<div class="layui-layout layui-layout-admin">
    <div class="layui-header">
        <div class="layui-logo"><a href="{{ route('web.index') }}">高可用全球免费代理IP库</a> </div>
        <ul class="layui-nav layui-layout-left">
            <li class="layui-nav-item">
                <a href="javascript:;">协议</a>
                <dl class="layui-nav-child">
                    <dd><a href="{{ route("web.index",['protocol' => 'http']) }}">HTTP</a></dd>
                    <dd><a href="{{ route("web.index",['protocol' => 'https']) }}">HTTPS</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:;">透明度</a>
                <dl class="layui-nav-child">
                    <dd><a href="{{ route("web.index",['anonymity' => '2']) }}">高匿</a></dd>
                    <dd><a href="{{ route("web.index",['anonymity' => '1']) }}">透明</a></dd>
                </dl>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:;">地区</a>
                <dl class="layui-nav-child">
                    @foreach($countries as $value)
                    <dd><a href="{{ route("web.index",['country' => $value]) }}">{{ $value }}</a></dd>
                    @endforeach
                </dl>
            </li>
            <li class="layui-nav-item">
                <a href="javascript:;">运营商</a>
                <dl class="layui-nav-child">
                    @foreach($isp as $value)
                        <dd><a href="{{ route("web.index",['isp' => $value]) }}">{{ $value }}</a></dd>
                    @endforeach
                </dl>
            </li>
            <li class="layui-nav-item">
                <a href="{{ route('blog.index') }}">每小时热门IP</a>
            </li>
        </ul>
    </div>

    <div class="layui-row ">
        <div class="container-wrap">
            <div class="container">
                <div class="contar-wrap">
                    <div class="item">
                        <div class="item-box  layer-photos-demo1 layer-photos-demo">
                            <h3>{{ sprintf("%s 全球最新免费HTTP代理IP",date("Y年m月d日H时",strtotime($blog['created_at']))) }}</h3>
                            <h5>发布于：<span>{{ $blog['created_at'] }}</span></h5>
                            <p> {!! $blog['content_html'] !!}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/layui/layui.all.js"></script>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/clipboard.js/1.5.16/clipboard.min.js"></script>
<script>
    // JavaScript代码区域
    layui.use('element', function(){
        var element = layui.element;

    });
</script>
</body>
</html>