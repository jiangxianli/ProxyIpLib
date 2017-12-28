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
                                    <button class="btn btn-sm btn-speed "
                                            data-url="{{ sprintf("%s://%s:%s",$proxy_ip->protocol,$proxy_ip->ip,$proxy_ip->port) }}"
                                            data-protocol="{{ $proxy_ip->protocol }}"
                                            data-ip="{{ $proxy_ip->ip }}"
                                            data-port="{{ $proxy_ip->port }}"
                                            data-unique-id="{{ $proxy_ip->unique_id }}">测速
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

            <div class="row">
                <p class="text-danger">&nbsp;&nbsp;&nbsp;&nbsp;代理IP采集于网络，请勿用于非法途径，违者后果自负！</p>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-speed" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="exampleModalLabel">代理IP测速</h4>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="recipient-name" class="control-label">代理地址</label>
                            <input type="text" class="form-control" id="proxy-ip-address">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="control-label">访问地址</label>
                            <input class="form-control" id="web-link" value="https://www.jiangxianli.com">
                        </div>
                        <div class="form-group">
                            <label for="message-text" class="control-label">访问结果</label>
                            <iframe class="form-control" id="proxy-iframe" style="min-height: 300px;" ></iframe>
                        </div>
                    </form>
                </div>
                {{--<div class="modal-footer">--}}
                    {{--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>--}}
                    {{--<button type="button" class="btn btn-primary">Send message</button>--}}
                {{--</div>--}}
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(function () {

            var loadModal = false;

            window.setInterval(function () {
                if (loadModal) {
                    return;
                }
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

            $('.btn-speed').click(function () {
                loadModal = true;
                $('#modal-speed').modal({
                    'backdrop': false,
                });
                var protocol = $(this).attr('data-protocol')
                var ip = $(this).attr('data-ip')
                var port = $(this).attr('data-port')
                var ipAddress = $(this).attr('data-url')
                var webLink = $('#web-link').val()
                $('#proxy-ip-address').val(ipAddress);
                var src = '/api/web-request-speed?protocol=' + protocol + '&ip=' + ip + '&port=' + port + '&web_link=' + encodeURIComponent(webLink);
                $('#proxy-iframe').attr('src', src)

            });

            $('#modal-speed').on('hidden.bs.modal', function (e) {
                loadModal = false;
            })
        });

    </script>
@endsection