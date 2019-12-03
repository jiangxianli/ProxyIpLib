<?php

namespace App\Http\Business;

use App\Http\Business\Dao\BlogDao;
use App\Http\Business\Dao\ProxyIpDao;

class SiteMapBusiness
{
    /**
     * 代理IP DAO
     *
     * @var ProxyIpDao
     */
    private $proxy_ip_dao;

    /**
     * @var BlogDao
     */
    private $blog_dao;

    /**
     * 构造函数
     *
     * ProxyIpBusiness constructor.
     * @param ProxyIpDao $proxy_ip_dao
     * @param BlogDao $blog_dao
     */
    public function __construct(ProxyIpDao $proxy_ip_dao, BlogDao $blog_dao)
    {
        $this->proxy_ip_dao = $proxy_ip_dao;
        $this->blog_dao = $blog_dao;
    }

    /**
     * @return array
     * @author jiangxianli
     * @created_at 2019-12-03 11:10
     */
    public function generateSiteMap()
    {
        //国家列表
        $countries = $this->proxy_ip_dao->allCountryList();
        //运营商列表
        $isp = $this->proxy_ip_dao->allIspList();
        //文章列表
        $blogs = app("BlogModel")->orderBy('created_at', 'desc')->limit(2000)->get();

        return compact('blogs', 'countries', 'isp');
    }

}