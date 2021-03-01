<?php

namespace App\Http\Business\Spider;

use QL\Ext\AbsoluteUrl;
use QL\QueryList;

class HtmlSpider extends BaseSpider
{
    /**
     * 一级页面地址
     *
     * @return array
     * @author jiangxianli
     * @created_at 2021-03-01 14:21
     */
    public function getUrls()
    {
        return [];
    }

    /**
     * table 选择器
     *
     * @var
     */
    protected $td_selector;

    /**
     * @param $tr
     * @return  array
     * @author jiangxianli
     * @created_at 2021-03-01 13:51
     */
    public function getItems($tr)
    {
        return [];
    }

    /**
     * 二级页面地址
     *
     * @author jiangxianli
     * @created_at 2021-03-01 14:16
     */
    public function getSecondPageUrls()
    {
       return [];
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author jiangxianli
     * @created_at 2021-03-01 14:24
     */
    public function handle()
    {
        $this->grabHtmlProcess($this->getSecondPageUrls(), [$this, "getItems"], $this->is_use_proxy);
    }
}