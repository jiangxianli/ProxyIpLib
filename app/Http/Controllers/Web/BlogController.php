<?php

namespace App\Http\Controllers\Web;

use App\Http\Business\ProxyIpBusiness;
use App\Http\Business\SiteMapBusiness;
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

    /**
     * 站点地图
     *
     * @param Request $request
     * @param SiteMapBusiness $site_map_business
     * @return \Illuminate\View\View
     * @author jiangxianli
     * @created_at 2019-12-03 11:10
     */
    public function siteMapXml(Request $request, SiteMapBusiness $site_map_business)
    {

        $view = app('cache')->remember("generated.sitemap.xml", 60, function () use ($site_map_business) {
            $response = $site_map_business->generateSiteMap();
            return view('sitemap.xml', $response)->render();
        });

        return response($view)->header('Content-Type', 'text/xml');
    }

    /**
     * 站点地图
     *
     * @param Request $request
     * @param SiteMapBusiness $site_map_business
     * @return \Illuminate\View\View
     * @author jiangxianli
     * @created_at 2019-12-03 11:10
     */
    public function siteMapTxt(Request $request, SiteMapBusiness $site_map_business)
    {
        $data = $site_map_business->generateSiteMap();

        $links = [];
        $links[] = route('web.index', [], false);
        foreach ($data['countries'] as $item) {
            $links[] = route("web.country",['area' => $item,'country' => $item], false);
        }
        foreach ($data['isp'] as $item) {
            $links[] = route('web.index', ['isp' => $item], false);
        }
        $links[] = route('blog.index', [], false);
        foreach ($data['blogs'] as $blog) {
            $links[] = route('blog.detail', ['blog_id' => $blog->id], false);
        }

        return response(implode("\n", $links))->header('Content-Type', 'text/plain');
    }

    /**
     * 站点地图
     *
     * @param Request $request
     * @param SiteMapBusiness $site_map_business
     * @return \Illuminate\View\View
     * @author jiangxianli
     * @created_at 2019-12-03 11:10
     */
    public function siteMapHtml(Request $request, SiteMapBusiness $site_map_business)
    {
        $view = app('cache')->remember("generated.sitemap.html", 60, function () use ($site_map_business) {
            $response = $site_map_business->generateSiteMap();
            return view('sitemap.html', $response)->render();
        });

        return response($view)->header('Content-Type', 'text/html');
    }
}