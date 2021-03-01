<?php

namespace App\Http\Business\Spider\Html;

use App\Http\Business\Spider\HtmlSpider;
use QL\Ext\AbsoluteUrl;
use QL\QueryList;

class XsDaiLiIpSpider extends HtmlSpider
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
        return [
            sprintf("http://www.xsdaili.com/dayProxy/%d/%d/1.html", date("Y"), date("m"))
        ];
    }

    /**
     * table 选择器
     *
     * @var
     */
    protected $td_selector = ".cont";

    /**
     * @param $tr
     * @return array
     * @author jiangxianli
     * @created_at 2021-03-01 14:27
     */
    public function getItems($tr)
    {
        $rows = [];
        $pattern = "/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}:\d{1,4}@(HTTPS|HTTP)#/";
        if (preg_match_all($pattern, $tr->htmls(), $matches)) {
            foreach ($matches[0] as $item) {
                $ip = substr($item, 0, strrpos($item, ":"));
                $port = substr($item, strrpos($item, ":") + 1, strrpos($item, "@") - strrpos($item, ":") - 1);
                $protocol = substr($item, strrpos($item, "@") + 1, strrpos($item, "#") - strrpos($item, "@") - 1);
                $rows[] = [$ip, $port, 2, strtolower($protocol)];
            }
        }

        return $rows;
    }

    /**
     * 二级页面地址
     *
     * @author jiangxianli
     * @created_at 2021-03-01 14:16
     */
    public function getSecondPageUrls()
    {
        $ql = QueryList::getInstance();
        $ql->use(AbsoluteUrl::class);
        $ql->use(AbsoluteUrl::class, 'absoluteUrl', 'absoluteUrlHelper');

        $urls = [];
        foreach ($this->getUrls() as $page_url) {
            $links = $ql->get($page_url)->absoluteUrl('http://www.xsdaili.com')->find('.title a')->attrs('href');
            $urls = array_merge($urls, $links->all());
        }
        return $urls;
    }

    /**
     * @author jiangxianli
     * @created_at 2021-03-01 17:15
     */
    public function handle()
    {
        $this->grabProcess($this->getSecondPageUrls(), $this->td_selector, [$this, "getItems"], $this->is_use_proxy);
    }
}