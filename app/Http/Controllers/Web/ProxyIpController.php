<?php

namespace App\Http\Controllers\Web;

use App\Http\Business\ProxyIpBusiness;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProxyIpController extends Controller
{
    /**
     * 首页
     *
     * @param Request $request
     * @param ProxyIpBusiness $proxy_ip_business
     * @return \Illuminate\View\View
     * @author jiangxianli
     * @created_at 2017-12-25 13:43:57
     */
    public function index(Request $request, ProxyIpBusiness $proxy_ip_business)
    {
        $condition = $request->all();
        $condition['order_by'] = 'validated_at';
        $condition['order_rule'] = 'desc';

        $proxy_ips = $proxy_ip_business->getProxyIpList($condition);

        return view('index', compact('proxy_ips'));
    }
}