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

        $response = $proxy_ip_business->indexPage($condition);

        return view('index', $response);
    }
}