<?php

namespace App\Http\Business\Spider;

use App\Http\Business\ProxyIpBusiness;

class BaseSpider extends ProxyIpBusiness
{
    /**
     * 是否开启抓取
     *
     * @var bool
     */
    public $is_open = true;
    /**
     * @var array
     */
    protected $urls = [];

    /**
     * @var bool
     */
    protected $is_use_proxy = true;

    /**
     * @author jiangxianli
     * @created_at 2021-03-01 16:38
     */
    public function handle()
    {
    }
}