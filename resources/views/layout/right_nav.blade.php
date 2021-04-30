<div class="layui-card">
    <div class="layui-card-header">网站公告</div>
    <div class="layui-card-body">
        <p style="color:#FF5722">代理IP均采集于网络，仅供学习使用。使用后不能保证数据安全性，重要数据传输请谨慎使用。请勿用于非法途径，后果自负！</p>
        <hr class="layui-bg-cyan">
        <p style="color:#1E9FFF">本站访问量并发比较大，麻烦采集页面时控制一下访问频率。</p>
    </div>
</div>
<div class="layui-card">
    <div class="layui-card-header">广告</div>
    <div class="layui-card-body">
        {{--<p>服务器租金贵，加带宽也要钱！难以维持下去，打个广告别介意哈！都是我从京东给你们找的实惠、高销量商品，如果需要可以点击购买！感激万分~</p>--}}
        <p>服务器租金贵，加带宽也要钱！难以维持下去，打个广告别介意哈！</p>
        <hr class="layui-bg-cyan">
        @foreach($ads as $ad)
            <div class="ad-content">
                {!! $ad->ad_content !!}
            </div>
            <hr class="layui-bg-cyan">
        @endforeach
        <p style="color:red">帮忙关注下公众号呗，淘宝、京东商品转链下单均可高额返利！~</p>
        <hr class="layui-bg-cyan">
        <div class="ad-card">
            <img src="{{ asset("/images/qrcode.png") }}" >
            {{--<img src="/ali-pay.png" class="ad-img">--}}
        </div>
    </div>
</div>