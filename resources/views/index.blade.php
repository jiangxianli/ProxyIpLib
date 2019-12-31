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
    @include('layout.common_js')
</head>
<body >
<div class="layui-layout layui-layout-admin">

    @include("layout.header")

    <div class="layui-row">
        <div class="layui-col-md9 ip-tables">
            <div class="layui-form">
                <table class="layui-table">
                    <thead>
                    <tr>
                        <th>IP</th>
                        <th>端口</th>
                        <th>匿名度</th>
                        <th>类型</th>
                        <th>位置</th>
                        <th>所属国</th>
                        <th>运营商</th>
                        <th>响应速度</th>
                        <th>存活时间</th>
                        <th>最后验证时间</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($proxy_ips as $key => $proxy_ip)
                        <tr>
                            <td>{{ $proxy_ip->ip }}</td>
                            <td>{{ $proxy_ip->port }}</td>
                            <td>{{ \App\Http\Common\Helper::formatAnonymity($proxy_ip->anonymity) }}</td>
                            <td>{{ strtoupper($proxy_ip->protocol) }}</td>
                            <td>{{ $proxy_ip->ip_address }}</td>
                            <td>{{ $proxy_ip->country }}</td>
                            <td>{{ $proxy_ip->isp }}</td>
                            <td>{{ \App\Http\Common\Helper::formatSpeed($proxy_ip->speed) }}</td>
                            <td>{{ \App\Http\Common\Helper::formatDateDay($proxy_ip->created_at) }}</td>
                            <td>{{ $proxy_ip->validated_at }}</td>
                            <td>
                                <button class="layui-btn layui-btn-sm btn-copy" data-url="{{ sprintf("%s://%s:%s",$proxy_ip->protocol,$proxy_ip->ip,$proxy_ip->port) }}" data-unique-id="{{ $proxy_ip->unique_id }}">复制</button>
                                <button class="layui-btn layui-btn-sm btn-speed " data-url="{{ sprintf("%s://%s:%s",$proxy_ip->protocol,$proxy_ip->ip,$proxy_ip->port) }}" data-protocol="{{ $proxy_ip->protocol }}" data-ip="{{ $proxy_ip->ip }}" data-port="{{ $proxy_ip->port }}"  data-unique-id="{{ $proxy_ip->unique_id }}">测速</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>


            <div id="paginate"></div>
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

    <div class="modal fade" id="modal-speed" tabindex="-1" style="display: none">
        <div class="modal-body">
            <form class="layui-form layui-form-pane">
                <div class="layui-form-item layui-form-text">
                    <label for="recipient-name" class="layui-form-label">代理地址</label>
                    <div class="layui-input-block">
                        <input type="text" class="layui-input" id="proxy-ip-address" readonly>
                    </div>
                    <input type="hidden" class="form-control" id="proxy-ip" >
                    <input type="hidden" class="form-control" id="proxy-port" >
                    <input type="hidden" class="form-control" id="proxy-protocol" >
                </div>
                <div class="layui-form-item layui-form-text">
                    <label for="message-text" class="layui-form-label">访问地址</label>
                    <div class="layui-input-block">
                        <input class="layui-input" id="web-link" value="http://www.baidu.com">
                    </div>
                </div>
                <div class="layui-form-item layui-form-text">
                    <label for="message-text" class="layui-form-label">访问结果</label>
                    <div class="layui-input-block">
                        <iframe class="layui-textarea" id="proxy-iframe" style="min-height: 300px;" name="proxy-iframe"></iframe>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{--<div class="content-footer"> Copyright © 2019 高可用全球免费代理IP库</div>--}}
</div>
<script src="/layui/layui.all.js"></script>
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/clipboard.js/1.5.16/clipboard.min.js"></script>
<script>
    // JavaScript代码区域
    layui.use('element', function(){
        var element = layui.element;

    });

    //页面参数
    var pagePrams = {
        page: "{{ $proxy_ips->currentPage() }}",
        protocol: "{{ isset($_GET['protocol']) ? $_GET['protocol'] : "" }}",
        anonymity: "{{ isset($_GET['anonymity']) ? $_GET['anonymity'] : "" }}",
        country: "{{ isset($_GET['country']) ? $_GET['country'] : "" }}",
        isp: "{{ isset($_GET['isp']) ? $_GET['isp'] : "" }}",
    };
    //页面配置
    var pageConfig = {
        autoRefresh: true,
        refreshIntervalTime: 10000,
    };

    //组装链接参数
    function makeUrlParams(obj) {
        var params = [];
        for (let key in obj) {
            if(obj[key] != ""){
                params.push(key + '=' + obj[key])
            }
        }
        return params.join("&")
    }
    //刷新页面
    function refreshPageAction() {
        window.location.href = "/?" + encodeURI(makeUrlParams(pagePrams))
    }
    //初始化自动刷新
    function initAutoRefresh() {
        window.setInterval(function () {
            if (!pageConfig.autoRefresh) {
                return;
            }
            refreshPageAction();
        }, pageConfig.refreshIntervalTime);
    }

    $(function () {

        initAutoRefresh();

        //分页渲染
        var laypage = layui.laypage
        laypage.render({
            elem: 'paginate',
            count: "{{ $proxy_ips->total() }}",
            limit: "{{ $proxy_ips->perPage() }}",
            layout: ['count', 'prev', 'page', 'next', 'skip'],
            curr: pagePrams.page,
            jump: function (obj, first) {
                if (!first) {
                    pagePrams.page = obj.curr
                    refreshPageAction();
                }
            }
        });

        //复制粘贴功能
        var clipboard = new Clipboard(".btn-copy", {
            text: function (_this) {
                return $(_this).attr('data-url');
            }
        });
        clipboard.on("success", function (t) {
            alert('复制成功!')
        }).on("error", function (t) {
            alert('复制失败!')
        });

        //提交测速
        function ipSpeed() {
            var src = '/api/web-request-speed?protocol=' + $("#proxy-protocol").val() + '&ip=' + $("#proxy-ip").val() + '&port=' + $("#proxy-port").val() + '&web_link=' + encodeURIComponent($('#web-link').val());
            $('#proxy-iframe').contents().find("html").html("");
            $('#proxy-iframe').attr('src', src)
        }
        $('.btn-speed').on('click', function () {
            $('#proxy-ip-address').val($(this).attr('data-url'));
            $("#proxy-ip").val($(this).attr('data-ip'));
            $("#proxy-port").val($(this).attr('data-port'));
            $("#proxy-protocol").val($(this).attr('data-protocol'));
            layer.open({
                type: 1,
                title: "IP测速",
                area: ['840px', '640px'],
                shadeClose: false, //点击遮罩关闭
                content: $('#modal-speed'),
                btn: ['立即测速'],
                yes: function () {
                    ipSpeed();
                },
                success: function (index, layero) {
                    pageConfig.autoRefresh = false;
                    ipSpeed();
                },
                cancel: function (index, layero) {
                    pageConfig.autoRefresh = true;
                }
            });
        });

        // $("#ad-1-image").html($("#f_f1").html())
    });
</script>
</body>
</html>