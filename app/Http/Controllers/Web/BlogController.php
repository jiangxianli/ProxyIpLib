<?php

namespace App\Http\Controllers\Web;

use App\Http\Business\ProxyIpBusiness;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogController extends Controller
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

        $response = $proxy_ip_business->blogIndexPage($condition);

        return view('blog.index', $response);
    }

    /**
     * 详情
     *
     * @param Request $request
     * @param ProxyIpBusiness $proxy_ip_business
     * @return \Illuminate\View\View
     * @author jiangxianli
     * @created_at 2017-12-25 13:43:57
     */
    public function detail(Request $request, ProxyIpBusiness $proxy_ip_business, $blog_id)
    {
        $response = $proxy_ip_business->blogDetailPage($blog_id);

        return view('blog.detail', $response);
    }
}