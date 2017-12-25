@extends('layout.html')

@section('body')
    <div class="row">
        <div class="center-block" style="width: 80%">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">免费代理IP库</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body table-responsive no-padding ">
                    <table class="table table-hover table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>IP</th>
                            <th>端口</th>
                            <th>匿名度</th>
                            <th>类型</th>
                            <th>位置</th>
                            <th>运营商</th>
                            <th>响应速度</th>
                            <th>最后验证时间</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($proxy_ips as $key => $proxy_ip)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $proxy_ip->ip }}</td>
                                <td>{{ $proxy_ip->port }}</td>
                                <td>{{ \App\Http\Common\Helper::formatAnonymity($proxy_ip->anonymity) }}</td>
                                <td>{{ strtoupper($proxy_ip->protocol) }}</td>
                                <td>{{ $proxy_ip->ip_address }}</td>
                                <td>{{ $proxy_ip->isp }}</td>
                                <td>{{ \App\Http\Common\Helper::formatSpeed($proxy_ip->speed) }}</td>
                                <td>{{ $proxy_ip->validated_at }}</td>
                                <td>
                                    <button class="btn btn-sm btn-copy"
                                            data-url="{{ sprintf("%s://%s:%s",$proxy_ip->protocol,$proxy_ip->ip,$proxy_ip->port) }}"
                                            data-unique-id="{{ $proxy_ip->unique_id }}">复制
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->

                {{ $proxy_ips->render() }}
            </div>
            <!-- /.box -->
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(function () {
            window.setInterval(function () {
                window.location.reload()
            }, 10000)


            var clipboard = new Clipboard(".btn-copy", {
                text: function (_this) {
                    return $(_this).attr('data-url');
                }
            });
            clipboard.on("success", function (t) {
                alert('复制成功!')
            });
            clipboard.on("error", function (t) {
                alert('复制失败!')
            });
        });

    </script>
@endsection