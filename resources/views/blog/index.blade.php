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
            <div class="layui-card">
                <div class="layui-card-header">网站公告</div>
                <div class="layui-card-body">
                    <p  style="color:#FF5722">代理IP均采集于网络，仅供学习使用。使用后不能保证数据安全性，重要数据传输请谨慎使用。请勿用于非法途径，后果自负！</p>
                    <hr class="layui-bg-cyan">
                    <p style="color:#1E9FFF">本站访问量并发比较大，麻烦采集页面时控制一下访问频率。</p>
                </div>
            </div>
            <div class="layui-card">
                <div class="layui-card-header">广告</div>
                <div class="layui-card-body">
                    <p  >服务器租金贵，加带宽也要钱！难以维持下去，打个广告别介意哈！麻烦动下你们发财的手点击一下广告哈。感激~</p>
                    <hr class="layui-bg-cyan">
                    @foreach($ads as $ad)
                        <div class="ad-content">
                            {!! $ad->ad_content !!}
                        </div>
                        <hr class="layui-bg-cyan">
                    @endforeach
                    {{--<hr class="layui-bg-cyan">--}}
                    {{--<div class="ad-2">--}}
                    {{--<script id="w2898_10507">(function () {var zy = document.createElement("script");var flowExchange = window.location.protocol.split(":")[0];var http = flowExchange === "https"?"https":"http";zy.src = http+"://exchange.2898.com/index/flowexchange/getGoods?id=10507&sign=c9a7c5527bca21f6ea3654c48b2fcb11";var s = document.getElementsByTagName("script");for(var i=0;i< s.length;i++){if(s[i].id){if(s[i].id == "w2898_10507"){s[i].parentNode.insertBefore(zy, s[i]);continue;}}}})();</script>--}}
                    {{--</div>--}}
                    {{--<div id="ad-1-image" class="ad-card"></div>--}}
                    {{--<p  >本项目已坚持无偿运行2年，您也可以直接小额支付请我喝杯咖啡，鼓励下呗~</p>--}}
                    {{--<hr class="layui-bg-cyan">--}}
                    {{--<div class="ad-card"><img src="https://cdn.shortpixel.ai/client/q_glossy,ret_img/https://www.jiangxianli.com/wp-content/uploads/2018/06/QQ%E6%88%AA%E5%9B%BE20180628153840.png"></div>--}}
                    {{--<div  class="ad-card"><img src="https://cdn.shortpixel.ai/client/q_glossy,ret_img/https://www.jiangxianli.com/wp-content/uploads/2018/06/QQ%E6%88%AA%E5%9B%BE20180628153858.png"></div>--}}
                </div>
            </div>
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
</script>
</body>
</html>