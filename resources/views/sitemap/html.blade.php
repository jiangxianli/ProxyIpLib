<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="免费代理IP,代理IP,高匿IP,优质IP,全球免费代理,最新IP"/>
    <meta name="description" content="全球免费代理IP库，高可用IP，精心筛选优质IP，2s必达,每秒持续更新"/>
    <title>站点地图 - 高可用全球免费代理IP库</title>
    <link rel="stylesheet" href="/layui/css/layui.css">
    <link rel="stylesheet" href="/css/main.css">
    @include('layout.common_js')
</head>
<body>
<div class="layui-layout layui-layout-admin">

    @include("layout.header")

    <div class="layui-row">
        <div class="layui-col-md6 layui-col-md-offset3 map">
            <div class="map-area">
                <h3>协议</h3>
                <ul>
                    <li><a href="{{ route("web.index",['protocol' => "http"]) }}">HTTP</a></li>
                    <li><a href="{{ route("web.index",['protocol' => "https"]) }}">HTTPS</a></li>
                </ul>
            </div>
            <div class="map-area">
                <h3>透明度</h3>
                <ul>
                    <li><a href="{{ route("web.index",['anonymity' => "2"]) }}">高匿</a></li>
                    <li><a href="{{ route("web.index",['anonymity' => "1"]) }}">透明</a></li>
                </ul>
            </div>
            <div class="map-area">
                <h3>区域</h3>
                <ul>
                    @foreach($countries as $item)
                        <li><a href="{{ route("web.country",['area' => $item,'country' => $item]) }}">{{ $item }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="map-area">
                <h3>运营商</h3>
                <ul>
                    @foreach($isp as $item)
                        <li><a href="{{ route('web.index',['isp' => $item]) }}">{{ $item }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="map-area">
                <h3>每小时热门IP</h3>
                <ul>
                    @foreach ($blogs as $blog)
                    <li>
                        <a href="{{ route('blog.detail',['blog_id'=>$blog->id]) }}">{{ date("Y年m月d日H时",strtotime($blog['created_at']))}}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>

@include("layout.footer")

<script src="/layui/layui.all.js"></script>
</body>
</html>