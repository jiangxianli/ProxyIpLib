<div class="layui-header">
    <div class="layui-logo"><a href="{{ route('web.index') }}">高可用全球免费代理IP库</a></div>
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