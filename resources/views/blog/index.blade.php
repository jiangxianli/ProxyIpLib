<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="免费代理IP,代理IP,高匿IP,优质IP,全球免费代理"/>
    <meta name="description" content="全球免费代理IP库，高可用IP，精心筛选优质IP,5s必达"/>
    <title>高可用全球免费代理IP库</title>
    <link rel="stylesheet" href="/layui/css/layui.css">
    <link rel="stylesheet" href="/css/main.css">
    <style>
        .content-footer {
            background: #eee;
            height: 44px;
            line-height: 44px;
            padding: 0 15px;;
        }
    </style>

    <!-- 百度统计 -->
    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?b72418f3b1d81bbcf8f99e6eb5d4e0c3";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>
    <!-- 自动收录推送 -->
    <script>
        (function () {
            var bp = document.createElement('script');
            var curProtocol = window.location.protocol.split(':')[0];
            if (curProtocol === 'https') {
                bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
            }
            else {
                bp.src = 'http://push.zhanzhang.baidu.com/push.js';
            }
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(bp, s);
        })();
    </script>
</head>
<body class="lay-blog">
<div class="layui-layout layui-layout-admin">
    <div class="layui-header">
        <div class="layui-logo">高可用全球免费代理IP库</div>
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

    <div class="layui-row layui-col-space1">
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