<?php

namespace App\Http\Controllers\Api;

use App\Http\Business\ProxyIpBusiness;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProxyIpController extends Controller
{
    /**
     * 获取一个验证通过的代理IP
     *
     * @param Request $request
     * @param ProxyIpBusiness $proxy_ip_business
     * @return ProxyIpBusiness
     * @author jiangxianli
     * @created_at 2017-12-25 15:00:37
     */
    public function getNowValidateOneIp(Request $request, ProxyIpBusiness $proxy_ip_business)
    {
        $condition = $request->all();

        $proxy_ip = $proxy_ip_business->getNowValidateOneProxyIp();

        return $this->jsonFormat($proxy_ip);
    }

    /**
     * 获取代理IP列表
     *
     * @param Request $request
     * @param ProxyIpBusiness $proxy_ip_business
     * @return array
     * @author jiangxianli
     * @created_at 2017-12-25 15:02:42
     */
    public function getProxyIpList(Request $request, ProxyIpBusiness $proxy_ip_business)
    {
        $condition = $request->all();

        $proxy_ips = $proxy_ip_business->getProxyIpList($condition);

        return $this->jsonFormat($proxy_ips);
    }

    /**
     * 网页代理IP请求测速
     *
     * @param Request $request
     * @param ProxyIpBusiness $proxy_ip_business
     * @return array
     * @author jiangxianli
     * @created_at 2017-12-26 15:18:46
     */
    public function proxyIpRequestWebSiteCheck(Request $request, ProxyIpBusiness $proxy_ip_business)
    {
        $ip_address = $request->get('ip_address');

        $web_link = $request->get('web_link');

        $proxy_ips = $proxy_ip_business->proxyIpRequestWebSiteCheck($ip_address, $web_link);

        return $this->jsonFormat($proxy_ips);
    }


}