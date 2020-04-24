<?php

namespace App\Http\Business;

use App\Exceptions\JsonException;
use App\Http\Business\Dao\AdDao;
use App\Http\Business\Dao\BlogDao;
use App\Http\Business\Dao\ProxyIpDao;
use App\Http\Common\Helper;
use App\Jobs\ClearProxyIpJob;
use App\Jobs\ProxyIpLocationJob;
use App\Jobs\SaveProxyIpJob;
use Carbon\Carbon;
use GuzzleHttp\Client;
use QL\Ext\AbsoluteUrl;
use QL\QueryList;

class ProxyIpBusiness
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
     * @var AdDao
     */
    private $ad_dao;

    /**
     * 请求超时时间
     *
     * @var
     */
    private $time_out = 8;

    /**
     * 日志路径
     *
     * @var string
     */
    private $log_path = 'proxy_ip';

    /**
     * 构造函数
     *
     * ProxyIpBusiness constructor.
     * @param ProxyIpDao $proxy_ip_dao
     * @param BlogDao $blog_dao
     * @param AdDao $ad_dao
     */
    public function __construct(ProxyIpDao $proxy_ip_dao, BlogDao $blog_dao, AdDao $ad_dao)
    {
        $this->proxy_ip_dao = $proxy_ip_dao;
        $this->blog_dao = $blog_dao;
        $this->ad_dao = $ad_dao;
    }

    /**
     * 抓取过程处理
     *
     * @param $urls
     * @param $table_selector
     * @param $map_func
     * @param bool $user_proxy
     * @author jiangxianli
     * @created_at 2017-12-28 14:42:03
     */
    protected function grabProcess($urls, $table_selector, $map_func, $user_proxy = false)
    {
        //遍历URL
        foreach ($urls as $url) {

            try {
                //记录抓取的URL
                app("Logger")->info("抓取URL", [$url]);
                //获取URL 域名
                $host = parse_url($url, PHP_URL_HOST);
                //
                $options = [
                    'headers' => [
                        'Referer'                   => "http://$host/",
                        'User-Agent'                => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.3 Safari/537.36",
                        'Accept'                    => "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
                        'Upgrade-Insecure-Requests' => "1",
                        'Host'                      => $host,
                        'DNT'                       => "1",
                    ],
                    'timeout' => $this->time_out
                ];

                //使用代理IP抓取
                if ($user_proxy) {
                    $proxy_ip = $this->getNowValidateOneProxyIp();
                    if ($proxy_ip) {
                        $options['proxy'] = $proxy_ip->ip . ":" . $proxy_ip->port;
                    }
                }

                $client = new Client();
                $request = $client->request("GET", $url, $options);

                //抓取网页内容
                $ql = QueryList::html($request->getBody()->getContents());
                //选中数据列表Table
                $table = $ql->find($table_selector);
                //遍历数据列
                $table->map(function ($tr) use ($map_func, $host) {
                    $ip = call_user_func_array($map_func, [$tr]);
                    $rows = count($ip) == count($ip, 1) ? [$ip] : $ip;
                    foreach ($rows as $row) {
                        //获取IP、端口、透明度、协议
                        list($ip, $port, $anonymity, $protocol) = $row;
                        //日志记录
                        app("Logger")->info("提取到IP", [$host, sprintf("%s://%s:%s", $protocol, $ip, $port)]);
                        //放入队列处理
                        dispatch(new SaveProxyIpJob($host, $ip, $port, $protocol, $anonymity));
                    }
                });

                unset($ql, $table);

            } catch (\Exception $exception) {
                //日志记录
                app("Logger")->error("抓取URL错误", [
                    'url'         => $url,
                    'error_code'  => $exception->getCode(),
                    'error_msg'   => $exception->getMessage(),
                    'error_trace' => $exception->getTraceAsString(),
                ]);
            }

            //延迟10秒抓取下一个网页
            sleep(10);
        }
    }

    /**
     * 抓取过程处理
     *
     * @param $urls
     * @param $table_selector
     * @param $map_func
     * @param bool $user_proxy
     * @author jiangxianli
     * @created_at 2017-12-28 14:42:03
     */
    protected function grabHtmlProcess($urls, $map_func, $user_proxy = false)
    {
        //遍历URL
        foreach ($urls as $url) {

            try {
                //记录抓取的URL
                app("Logger")->info("抓取URL", [$url]);
                //获取URL 域名
                $host = parse_url($url, PHP_URL_HOST);
                //
                $options = [
                    'headers' => [
                        'Referer'                   => "http://$host/",
                        'User-Agent'                => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.3 Safari/537.36",
                        'Accept'                    => "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
                        'Upgrade-Insecure-Requests' => "1",
                        'Host'                      => $host,
                        'DNT'                       => "1",
                    ],
                    'timeout' => $this->time_out
                ];

                //使用代理IP抓取
                if ($user_proxy) {
                    $proxy_ip = $this->getNowValidateOneProxyIp();
                    if ($proxy_ip) {
                        $options['proxy'] = $proxy_ip->ip . ":" . $proxy_ip->port;
                    }
                }

                $client = new Client();
                $request = $client->request("GET", $url, $options);

                //抓取网页内容
                $ql = QueryList::html($request->getBody()->getContents());
                //选中数据列表Table
                $html = $ql->getHtml();
                //遍历数据列
                $ip = call_user_func_array($map_func, [$html]);
                $rows = count($ip) == count($ip, 1) ? [$ip] : $ip;
                foreach ($rows as $row) {
                    //获取IP、端口、透明度、协议
                    list($ip, $port, $anonymity, $protocol) = $row;
                    //日志记录
                    app("Logger")->info("提取到IP", [$host, sprintf("%s://%s:%s", $protocol, $ip, $port)]);
                    //放入队列处理
                    dispatch(new SaveProxyIpJob($host, $ip, $port, $protocol, $anonymity));
                }

                unset($ql, $table);

            } catch (\Exception $exception) {
                //日志记录
                app("Logger")->error("抓取URL错误", [
                    'url'         => $url,
                    'error_code'  => $exception->getCode(),
                    'error_msg'   => $exception->getMessage(),
                    'error_trace' => $exception->getTraceAsString(),
                ]);
            }

            //延迟10秒抓取下一个网页
            sleep(10);
        }
    }

    /**
     * 抓取快代理IP
     *
     * @author jiangxianli
     * @created_at 2017-12-25 08:45:20
     */
    public function grabKuaiDaiLi()
    {
        $urls = [
            "http://www.kuaidaili.com/free/inha/",
            "http://www.kuaidaili.com/free/inha/2/",
            "http://www.kuaidaili.com/free/inha/3/",
            "http://www.kuaidaili.com/free/inha/4/",
            "http://www.kuaidaili.com/free/inha/5/",
            "http://www.kuaidaili.com/free/intr/",
            "http://www.kuaidaili.com/free/intr/2/",
            "http://www.kuaidaili.com/free/intr/3/",
            "http://www.kuaidaili.com/free/intr/4/",
            "http://www.kuaidaili.com/free/intr/5/",
        ];

        $this->grabProcess($urls, "#list table tr", function ($tr) {
            $ip = $tr->find('td:eq(0)')->text();
            $port = $tr->find('td:eq(1)')->text();
            $anonymity = $tr->find('td:eq(2)')->text() == "高匿名" ? 2 : 1;
            $protocol = strtolower($tr->find('td:eq(3)')->text());
            return [$ip, $port, $anonymity, $protocol];
        }, true);
    }

    /**
     * IP3366
     *
     * @author jiangxianli
     * @created_at 2017-12-25 18:01:44
     */
    public function grabIp3366()
    {
        $urls = [
            "http://www.ip3366.net/free/?stype=1&page=1",
            "http://www.ip3366.net/free/?stype=1&page=2",
            "http://www.ip3366.net/free/?stype=1&page=3",
            "http://www.ip3366.net/free/?stype=1&page=4",
            "http://www.ip3366.net/free/?stype=2&page=1",
            "http://www.ip3366.net/free/?stype=2&page=2",
            "http://www.ip3366.net/free/?stype=2&page=3",
            "http://www.ip3366.net/free/?stype=2&page=4",
            "http://www.ip3366.net/free/?stype=3&page=1",
            "http://www.ip3366.net/free/?stype=3&page=2",
            "http://www.ip3366.net/free/?stype=3&page=3",
            "http://www.ip3366.net/free/?stype=3&page=4",
            "http://www.ip3366.net/free/?stype=4&page=1",
            "http://www.ip3366.net/free/?stype=4&page=2",
            "http://www.ip3366.net/free/?stype=4&page=3",
            "http://www.ip3366.net/free/?stype=4&page=4",
        ];

        $this->grabProcess($urls, "#list table tr", function ($tr) {
            $ip = $tr->find('td:eq(0)')->text();
            $port = $tr->find('td:eq(1)')->text();
            $anonymity = str_contains($tr->find('td:eq(2)')->text(), ["高匿"]) ? 2 : 1;
            $protocol = strtolower($tr->find('td:eq(3)')->text());
            return [$ip, $port, $anonymity, $protocol];
        }, true);

    }

    /**
     * IP3366
     *
     * @author jiangxianli
     * @created_at 2017-12-25 18:01:44
     */
    public function grab89Ip()
    {
        $urls = [
            "http://www.89ip.cn/index_1.html",
            "http://www.89ip.cn/index_2.html",
            "http://www.89ip.cn/index_3.html",
            "http://www.89ip.cn/index_4.html",
            "http://www.89ip.cn/index_5.html",
            "http://www.89ip.cn/index_6.html",
            "http://www.89ip.cn/index_7.html",
            "http://www.89ip.cn/index_8.html",
            "http://www.89ip.cn/index_9.html",
            "http://www.89ip.cn/index_10.html",
            "http://www.89ip.cn/index_11.html",
            "http://www.89ip.cn/index_12.html",
            "http://www.89ip.cn/index_13.html",
            "http://www.89ip.cn/index_14.html",
            "http://www.89ip.cn/index_15.html",
        ];

        $this->grabProcess($urls, "table.layui-table tbody tr", function ($tr) {
            $ip = $tr->find('td:eq(0)')->text();
            $port = $tr->find('td:eq(1)')->text();
            $anonymity = 2;
            $protocol = "http";
            return [$ip, $port, $anonymity, $protocol];
        }, true);
    }

    /**
     * @author jiangxianli
     * @created_at 2019-10-28 14:31
     */
    public function xiLaIp()
    {
        $urls = [
            "http://www.xiladaili.com/gaoni/",
            "http://www.xiladaili.com/gaoni/2/",
            "http://www.xiladaili.com/gaoni/3/",
            "http://www.xiladaili.com/gaoni/4/",
            "http://www.xiladaili.com/gaoni/5/",
            "http://www.xiladaili.com/gaoni/6/",
            "http://www.xiladaili.com/putong/",
            "http://www.xiladaili.com/putong/2/",
            "http://www.xiladaili.com/putong/3/",
            "http://www.xiladaili.com/putong/4/",
            "http://www.xiladaili.com/putong/5/",
            "http://www.xiladaili.com/putong/6/",
        ];

        $this->grabProcess($urls, "table.fl-table tbody tr", function ($tr) {
            list($ip, $port) = explode(":", $tr->find('td:eq(0)')->text());
            $protocol = str_contains($tr->find('td:eq(1)')->text(), "HTTPS") ? "https" : "http";
            $anonymity = str_contains($tr->find('td:eq(1)')->text(), "透明") ? 1 : 2;
            return [$ip, $port, $anonymity, $protocol];
        }, true);
    }

    /**
     * @author jiangxianli
     * @created_at 2019-10-28 14:31
     */
    public function emailtryIp()
    {
        $urls = [
            "http://emailtry.com/index/1",
            "http://emailtry.com/index/2",
            "http://emailtry.com/index/3",
            "http://emailtry.com/index/4",
            "http://emailtry.com/index/5",
            "http://emailtry.com/index/6",
            "http://emailtry.com/index/7",
            "http://emailtry.com/index/8",
            "http://emailtry.com/index/9",
            "http://emailtry.com/index/10",
        ];

        $this->grabProcess($urls, "table#proxy-table1>tr", function ($tr) {
            list($ip, $port) = explode(":", $tr->find('td:eq(0)')->text());
            $protocol = "http";
            $anonymity = 2;
            return [$ip, $port, $anonymity, $protocol];
        }, true);
    }

    /**
     * @author jiangxianli
     * @created_at 2019-10-28 14:31
     */
    public function qinghuaIp()
    {
        $urls = [
            "http://www.qinghuadaili.com/free/1/",
            "http://www.qinghuadaili.com/free/2/",
            "http://www.qinghuadaili.com/free/3/",
            "http://www.qinghuadaili.com/free/4/",
            "http://www.qinghuadaili.com/free/5/",
            "http://www.qinghuadaili.com/free/6/",
            "http://www.qinghuadaili.com/free/7/",
            "http://www.qinghuadaili.com/free/8/",
            "http://www.qinghuadaili.com/free/9/",
            "http://www.qinghuadaili.com/free/10/",
        ];

        $this->grabProcess($urls, ".container-fluid table tbody tr", function ($tr) {
            $ip = trim($tr->find('td:eq(0)')->text());
            $port = trim($tr->find('td:eq(1)')->text());
            $anonymity = str_contains($tr->find('td:eq(2)')->text(), "高匿") ? 2 : 1;
            $protocol = str_contains($tr->find('td:eq(3)')->text(), "HTTPS") ? "https" : "http";
            return [$ip, $port, $anonymity, $protocol];
        }, true);
    }

    /**
     * @author jiangxianli
     * @created_at 2019-10-28 14:31
     */
    public function kxdailiIp()
    {
        $urls = [
            "http://www.kxdaili.com/dailiip.html",
            "http://www.kxdaili.com/dailiip/1/2.html",
            "http://www.kxdaili.com/dailiip/1/3.html",
            "http://www.kxdaili.com/dailiip/1/4.html",
            "http://www.kxdaili.com/dailiip/1/5.html",
            "http://www.kxdaili.com/dailiip/1/6.html",
            "http://www.kxdaili.com/dailiip/1/7.html",
            "http://www.kxdaili.com/dailiip/2/1.html",
            "http://www.kxdaili.com/dailiip/2/2.html",
            "http://www.kxdaili.com/dailiip/2/3.html",
            "http://www.kxdaili.com/dailiip/2/4.html",
            "http://www.kxdaili.com/dailiip/2/5.html",
        ];

        $this->grabProcess($urls, ".hot-product-content table tbody tr", function ($tr) {
            $ip = trim($tr->find('td:eq(0)')->text());
            $port = trim($tr->find('td:eq(1)')->text());
            $anonymity = str_contains($tr->find('td:eq(2)')->text(), "高匿") ? 2 : 1;
            $protocol = str_contains($tr->find('td:eq(3)')->text(), "HTTPS") ? "https" : "http";
            return [$ip, $port, $anonymity, $protocol];
        }, true);
    }

    /**
     * @author jiangxianli
     * @created_at 2019-10-28 14:31
     */
    public function nimaIp()
    {
        $urls = [
            "http://www.nimadaili.com/putong/",
            "http://www.nimadaili.com/putong/2/",
            "http://www.nimadaili.com/putong/3/",
            "http://www.nimadaili.com/putong/4/",
            "http://www.nimadaili.com/putong/5/",
            "http://www.nimadaili.com/gaoni/1/",
            "http://www.nimadaili.com/gaoni/2/",
            "http://www.nimadaili.com/gaoni/3/",
            "http://www.nimadaili.com/gaoni/4/",
            "http://www.nimadaili.com/gaoni/5/",
            "http://www.nimadaili.com/http/1/",
            "http://www.nimadaili.com/http/2/",
            "http://www.nimadaili.com/http/3/",
            "http://www.nimadaili.com/http/4/",
            "http://www.nimadaili.com/http/5/",
            "http://www.nimadaili.com/https/1/",
            "http://www.nimadaili.com/https/2/",
            "http://www.nimadaili.com/https/3/",
            "http://www.nimadaili.com/https/4/",
            "http://www.nimadaili.com/https/5/",
        ];

        $this->grabProcess($urls, "table.fl-table tbody tr", function ($tr) {
            list($ip, $port) = explode(":", $tr->find('td:eq(0)')->text());
            $protocol = str_contains($tr->find('td:eq(1)')->text(), "HTTPS") ? "https" : "http";
            $anonymity = str_contains($tr->find('td:eq(1)')->text(), "普通") ? 1 : 2;
            return [$ip, $port, $anonymity, $protocol];
        }, true);
    }

    /**
     * @author jiangxianli
     * @created_at 2019-10-28 14:31
     */
    public function superIp()
    {
        $urls = [
            "http://www.superfastip.com/welcome/freeip/1",
            "http://www.superfastip.com/welcome/freeip/2",
            "http://www.superfastip.com/welcome/freeip/3",
            "http://www.superfastip.com/welcome/freeip/4",
            "http://www.superfastip.com/welcome/freeip/5",
            "http://www.superfastip.com/welcome/freeip/6",
            "http://www.superfastip.com/welcome/freeip/7",
            "http://www.superfastip.com/welcome/freeip/8",
            "http://www.superfastip.com/welcome/freeip/9",
            "http://www.superfastip.com/welcome/freeip/10",
        ];

        $this->grabProcess($urls, "table tbody tr", function ($tr) {
            $ip = trim($tr->find('td:eq(0)')->text());
            $port = trim($tr->find('td:eq(1)')->text());
            $anonymity = 2;
            $protocol = str_contains($tr->find('td:eq(3)')->text(), "HTTPS") ? "https" : "http";
            return [$ip, $port, $anonymity, $protocol];
        }, true);
    }

    /**
     * @author jiangxianli
     * @created_at 2019-10-28 14:31
     */
    public function xsdailiIp()
    {
        $ql = QueryList::getInstance();
        $ql->use(AbsoluteUrl::class);
        $ql->use(AbsoluteUrl::class, 'absoluteUrl', 'absoluteUrlHelper');
        $page_url = sprintf("http://www.xsdaili.com/dayProxy/%d/%d/1.html", date("Y"), date("m"));
        $urls = $ql->get($page_url)->absoluteUrl('http://www.xsdaili.com')->find('.title a')->attrs('href');

        $this->grabProcess($urls, ".cont", function ($tr) {
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
        }, true);
    }


    /**
     * @author jiangxianli
     * @created_at 2019-10-28 14:31
     */
    public function xiciIp()
    {
        $urls = [
            "https://www.xicidaili.com/nn/",
            "https://www.xicidaili.com/nn/2",
            "https://www.xicidaili.com/nn/3",
            "https://www.xicidaili.com/nt/",
            "https://www.xicidaili.com/nt/2",
            "https://www.xicidaili.com/nt/3",
            "https://www.xicidaili.com/wn/",
            "https://www.xicidaili.com/wn/2",
            "https://www.xicidaili.com/wn/3",
            "https://www.xicidaili.com/wt/",
            "https://www.xicidaili.com/wt/2",
            "https://www.xicidaili.com/wt/3",
        ];

        $this->grabProcess($urls, "#ip_list tr", function ($tr) {
            $ip = trim($tr->find('td:eq(1)')->text());
            $port = trim($tr->find('td:eq(2)')->text());
            $anonymity = str_contains($tr->find('td:eq(4)')->text(), "高匿") ? 2 : 1;
            $protocol = str_contains($tr->find('td:eq(5)')->text(), "HTTPS") ? "https" : "http";
            return [$ip, $port, $anonymity, $protocol];
        }, true);
    }

    /**
     * @author jiangxianli
     * @created_at 2019-10-28 14:31
     */
    public function foxtoolsIp()
    {
        $urls = [
            "http://api.foxtools.ru/v2/Proxy.txt?page=1",
        ];

        $this->grabHtmlProcess($urls, function ($html) {
            $rows = [];
            $lines = explode("\n", $html);
            foreach ($lines as $line) {
                if (!str_contains($line, ":")) {
                    continue;
                }
                $line = str_replace("\r", "", $line);
                $line = str_replace("\n", "", $line);
                list($ip, $port) = explode(":", $line);
                $anonymity = 1;
                $protocol = "https";
                $rows[] = [$ip, $port, $anonymity, $protocol];
            }
            return $rows;
        }, true);
    }

    /**
     * @author jiangxianli
     * @created_at 2019-10-28 14:31
     */
    public function proxyListIp()
    {
        $urls = [
            "https://www.proxy-list.download/api/v1/get?type=http",
            "https://www.proxy-list.download/api/v1/get?type=https",
        ];

        $this->grabHtmlProcess($urls, function ($html) {
            $rows = [];
            $lines = explode("\n", $html);
            foreach ($lines as $line) {
                if (!str_contains($line, ":")) {
                    continue;
                }
                $line = str_replace("\r", "", $line);
                $line = str_replace("\n", "", $line);
                list($ip, $port) = explode(":", $line);
                $anonymity = 1;
                $protocol = "http";
                $rows[] = [$ip, $port, $anonymity, $protocol];
            }
            return $rows;
        }, true);
    }

    /**
     * @author jiangxianli
     * @created_at 2019-10-28 14:31
     */
    public function proxylistmeIp()
    {
        $urls = [
            "https://proxylist.me/?page=1",
            "https://proxylist.me/?page=2",
            "https://proxylist.me/?page=3",
            "https://proxylist.me/?page=4",
            "https://proxylist.me/?page=5",
            "https://proxylist.me/?page=6",
        ];

        $this->grabProcess($urls, "#datatable-row-highlight tr", function ($tr) {
            $ip = trim($tr->find('td:eq(0) a')->text());
            $port = trim($tr->find('td:eq(1)')->text());
            $anonymity = 1;
            $protocol = str_contains($tr->find('td:eq(3)')->text(), "https") ? "https" : "http";
            return [$ip, $port, $anonymity, $protocol];
        }, true);
    }

    /**
     * 定时清理
     *
     * @author jiangxianli
     * @created_at 2017-12-25 10:38:13
     */
    public function timerClearProxyIp()
    {
        $page = 1;
        $page_size = 200;

        while (true) {

            $condition = [
                'order_by'   => 'validated_at',
                'order_rule' => 'asc',
                'page'       => $page++,
                'page_size'  => $page_size
            ];
            $columns = ['unique_id', 'ip', 'port', 'protocol'];
            $proxy_ips = $this->proxy_ip_dao->getProxyIpList($condition, $columns);
            if ($proxy_ips->count() <= 0) {
                break;
            }

            foreach ($proxy_ips as $proxy_ip) {
                dispatch(new ClearProxyIpJob($proxy_ip->toArray()));
            }
        }
    }

    /**
     * 添加代理IP
     *
     * @param $host
     * @param $ip
     * @param $port
     * @param $protocol
     * @param $anonymity
     * @throws JsonException
     * @author jiangxianli
     * @created_at 2017-12-22 16:52:09
     */
    public function addProxyIp($host, $ip, $port, $protocol, $anonymity)
    {
        //查询IP唯一性
        $proxy_ip = $this->proxy_ip_dao->findUniqueProxyIp($ip, $port, $protocol);
        if ($proxy_ip) {
            app("Logger")->error("数据库已存在该IP地址", [$ip, $port, $protocol]);
            return;
        }

        //响应速度
        $speed = $this->ipSpeedCheck($ip, $port, $protocol);

        $ip_data = [
            'unique_id'    => Helper::unique_id(),
            'ip'           => $ip,
            'port'         => $port,
            'anonymity'    => $anonymity,
            'protocol'     => $protocol,
            'speed'        => $speed,
            'validated_at' => Carbon::now(),
        ];
        $proxy_ip = $this->proxy_ip_dao->addProxyIp($ip_data);
        app("Logger")->info("新IP入库成功", [$host, $ip_data]);

        dispatch(new ProxyIpLocationJob($proxy_ip->toArray()));
    }

    /**
     * IP 地址定位
     *
     * @param $ip
     * @return array
     * @throws JsonException
     * @author jiangxianli
     * @created_at 2017-12-22 16:39:58
     */
    public function ipLocation($ip)
    {
        //间隔10秒请求一次
        sleep(10);

        $random = rand(1, 4);

        switch ($random) {
            case 1:
                return $this->taobaoIpLocation($ip);
                break;
            case  2:
                return $this->juheIpLocation($ip);
                break;
            case 3:
                return $this->apiIpLocation($ip);
                break;
            case 4:
                return $this->tianqiIpLocation($ip);
                break;
        }
    }

    /**
     * 淘宝IP库
     *
     * @param $ip
     * @return mixed
     * @throws JsonException
     * @author jiangxianli
     * @created_at 2020-03-02 13:28
     */
    private function taobaoIpLocation($ip)
    {
        //API 地址
        $api = "http://ip.taobao.com/service/getIpInfo.php?ip=" . $ip;
        $client = new Client();
        $request = $client->request("GET", $api);
        //响应json数据
        $json = $request->getBody()->getContents();
        //转数组格式
        $data = (array)json_decode($json, true);

        if (!isset($data['code']) || $data['code'] != 0) {
            throw new JsonException(90000, $data);
        }

        return $data['data'];
    }


    /**
     * 聚合IP库
     *
     * @param $ip
     * @return mixed
     * @throws JsonException
     * @author jiangxianli
     * @created_at 2020-03-02 13:28
     */
    private function juheIpLocation($ip)
    {
        //API 地址
        $api = "https://apis.juhe.cn/ip/Example/query.php";
        $client = new Client();
        $request = $client->request("POST", $api, [
            'form_params' => [
                'IP' => $ip
            ]
        ]);
        //响应json数据
        $json = $request->getBody()->getContents();
        //转数组格式
        $data = (array)json_decode($json, true);

        if (!isset($data['resultcode']) || $data['resultcode'] != 200) {
            throw new JsonException(90000, $data);
        }

        return [
            'country' => $data['result']['Country'],
            'region'  => $data['result']['Province'],
            'city'    => $data['result']['City'],
            'isp'     => $data['result']['Isp'],
        ];
    }

    /**
     * 聚合IP库
     *
     * @param $ip
     * @return mixed
     * @throws JsonException
     * @author jiangxianli
     * @created_at 2020-03-02 13:28
     */
    private function apiIpLocation($ip)
    {
        //API 地址
        $api = "http://ip-api.com/json/" . $ip . "?lang=zh-CN";
        $client = new Client();
        $request = $client->request("GET", $api);
        //响应json数据
        $json = $request->getBody()->getContents();
        //转数组格式
        $data = (array)json_decode($json, true);

        if (!isset($data['status']) || $data['status'] != "success") {
            throw new JsonException(90000, $data);
        }

        return [
            'country' => $data['country'],
            'region'  => $data['regionName'],
            'city'    => $data['city'],
            'isp'     => $data['isp'],
        ];
    }

    /**
     * 聚合IP库
     *
     * @param $ip
     * @return mixed
     * @throws JsonException
     * @author jiangxianli
     * @created_at 2020-03-02 13:28
     */
    private function tianqiIpLocation($ip)
    {
        //API 地址
        $api = "http://ip.tianqiapi.com/?ip=" . $ip;
        $client = new Client();
        $request = $client->request("GET", $api);
        //响应json数据
        $json = $request->getBody()->getContents();
        //转数组格式
        $data = (array)json_decode($json, true);

        if (!isset($data['country']) || !isset($data['ip'])) {
            throw new JsonException(90000, $data);
        }

        return [
            'country' => $data['country'],
            'region'  => $data['province'],
            'city'    => $data['city'],
            'isp'     => $data['isp'],
        ];
    }

    /**
     * IP 访问速度测试
     *
     * @param $ip
     * @param $port
     * @param $protocol
     * @return int
     * @author jiangxianli
     * @created_at 2017-12-22 16:50:31
     */
    public function ipSpeedCheck($ip, $port, $protocol)
    {
        //开始请求毫秒
        $begin_seconds = Helper::mSecondTime();

        $url = "http://www.baidu.com";
        //
        $options = [
            'headers' => [
                'Referer'                   => $url,
                'User-Agent'                => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.3 Safari/537.36",
                'Accept'                    => "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
                'Upgrade-Insecure-Requests' => "1",
                'Host'                      => parse_url($url, PHP_URL_HOST),
                'DNT'                       => "1",
            ],
            'timeout' => config('site.speed_limit') / 1000,
            'proxy'   => "$ip:$port"
        ];

        $client = new Client();
        $request = $client->request("GET", $url, $options);
        $content = $request->getBody()->getContents();

        //抓取网页内容
        $ql = QueryList::html($content);
        //获取标题
        $title = $ql->find("title")->eq(0)->text();
        //销毁
        $ql->destruct();

        if (empty($title) || !str_contains($title, "百度一下")) {
            throw new JsonException(20000);
        }

        $end_seconds = Helper::mSecondTime();
        //总用时 (大于)
        $total_use = intval($end_seconds - $begin_seconds);
        if ($total_use > config('site.speed_limit')) {
            throw new JsonException(20001, [config('site.speed_limit'), $total_use]);
        }

        return $total_use;
    }

    /**
     * 代理IP列表
     *
     * @param array $condition
     * @return mixed
     * @author jiangxianli
     * @created_at 2017-12-25 13:42:39
     */
    public function getProxyIpList(array $condition = [])
    {
        $proxy_ips = $this->proxy_ip_dao->getProxyIpList($condition);

        return $proxy_ips;
    }

    /**
     * 更新代理IP信息
     *
     * @param $unique_id
     * @param array $update_arr
     * @throws JsonException
     * @author jiangxianli
     * @created_at 2019-10-23 16:02
     */
    public function updateProxyIp($unique_id, array $update_arr)
    {
        $this->proxy_ip_dao->updateProxyIp($unique_id, $update_arr);
    }

    /**
     * 删除代理IP信息
     *
     * @param $unique_id
     * @throws JsonException
     * @author jiangxianli
     * @created_at 2019-10-23 16:02
     */
    public function deleteProxyIp($unique_id)
    {
        $this->proxy_ip_dao->deleteProxyIp($unique_id);
    }

    /**
     * 获取一个验证通过的IP
     *
     * @return mixed
     * @author jiangxianli
     * @created_at 2017-12-25 14:59:57
     */
    public function getNowValidateOneProxyIp()
    {
        $condition = [
            'order_by'   => 'validated_at',
            'order_rule' => 'desc',
            'first'      => 'true'
        ];
        $proxy_ip = $this->proxy_ip_dao->getProxyIpList($condition);

        return $proxy_ip;
    }

    /**
     * 代理IP 网页访问测试
     *
     * @param $protocol
     * @param $ip
     * @param $port
     * @param $web_link
     * @return string
     * @author jiangxianli
     * @created_at 2017-12-26 16:08:30
     */
    public function proxyIpRequestWebSiteCheck($protocol, $ip, $port, $web_link)
    {
        //代理请求
        $client = new Client();
        $response = $client->request('GET', $web_link, [
            'proxy'   => "$ip:$port",
            'timeout' => $this->time_out
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * IP 地址定位
     *
     * @author jiangxianli
     * @created_at 2017-12-27 09:39:47
     */
    public function locationAllProxyIp()
    {
        $proxy_ips = $this->proxy_ip_dao->allNoIpAddressProxyIp();

        foreach ($proxy_ips as $proxy_ip) {
            dispatch(new ProxyIpLocationJob($proxy_ip));
        }
    }

    /**
     * 站点列表
     *
     * @return array
     * @author jiangxianli
     * @created_at 2017-12-28 14:06:37
     */
    protected function getWebUrls()
    {
        return [
            "http://www.sina.com.cn/",
            "http://www.163.com/",
            "http://game.2345.com/",
            "http://email.163.com/",
            "http://www.youku.com/",
            "http://www.xxsy.net/",
            "http://www.sznews.com/",
            "http://www.dayoo.com/",
            "http://www.meizhou.cn/",
            "http://www.infzm.com/",
            "http://www.southcn.com/",
            "http://www.gdtv.cn/",
            "http://lady.163.com/",
            "http://guangzhou.baixing.com/",
            "http://www.xiaozhu.com/",
            "http://www.jiayuan.com/",
        ];
    }

    /**
     * @param array $condition
     * @return array
     * @author jiangxianli
     * @created_at 2019-11-07 15:53
     */
    public function indexPage(array $condition)
    {
        //IP 列表
        $condition['order_by'] = 'validated_at';
        $condition['order_rule'] = 'desc';
        $proxy_ips = $this->getProxyIpList($condition);

        //国家列表
        $countries = $this->proxy_ip_dao->allCountryList();
        //运营商列表
        $isp = $this->proxy_ip_dao->allIspList();
        //广告
        $ads = $this->cacheAdList([
            'area'    => 'web_index',
            'limit'   => 2,
            'is_show' => "yes",
        ]);

        return compact('proxy_ips', 'countries', 'isp', 'ads');
    }

    /**
     * 每小时热门IP
     *
     * @throws JsonException
     * @author jiangxianli
     * @created_at 2019-11-19 14:34
     */
    public function initHourBlog()
    {
        $condition = [
            'order_by'   => 'validated_at',
            'order_rule' => 'desc',
            'limit'      => 50
        ];
        $proxy_ips = $this->proxy_ip_dao->getProxyIpList($condition, ['ip', 'port', 'protocol', 'country', 'anonymity', 'ip_address', 'isp'])->toArray();

        $store_data = [
            'date_time' => date("YmdH"),
            'content'   => json_encode($proxy_ips)
        ];
        $this->blog_dao->addBlog($store_data);
    }

    /**
     * @param array $condition
     * @return array
     * @author jiangxianli
     * @created_at 2019-11-07 15:53
     */
    public function blogIndexPage(array $condition)
    {
        $condition['page_size'] = 15;
        $condition['order_by'] = 'date_time';
        $condition['order_rule'] = 'desc';

        $blogs = $this->blog_dao->getBlogList($condition);
        //国家列表
        $countries = $this->proxy_ip_dao->allCountryList();
        //运营商列表
        $isp = $this->proxy_ip_dao->allIspList();
        //广告
        $ads = $this->cacheAdList([
            'area'    => 'blog_index',
            'limit'   => 2,
            'is_show' => "yes",
        ]);

        return compact('blogs', 'countries', 'isp', 'ads');
    }

    /**
     * @param $blog_id
     * @return array
     * @author jiangxianli
     * @created_at 2019-11-19 15:08
     */
    public function blogDetailPage($blog_id)
    {
        $condition = [
            'id'    => $blog_id,
            'first' => 'true'
        ];
        $blog = $this->blog_dao->getBlogList($condition);
        //国家列表
        $countries = $this->proxy_ip_dao->allCountryList();
        //运营商列表
        $isp = $this->proxy_ip_dao->allIspList();
        //广告
        $ads = $this->cacheAdList([
            'area'    => 'blog_detail',
            'limit'   => 2,
            'is_show' => "yes",
        ]);

        return compact('blog', 'countries', 'isp', 'ads');
    }

    /**
     * 缓存广告
     *
     * @param array $condition
     * @return mixed
     * @author jiangxianli
     * @created_at 2020-03-04 16:50
     */
    private function cacheAdList(array $condition)
    {

        $ads = app('cache')->remember("cache_ad." . md5(http_build_query($condition)), 2, function () use ($condition) {
            //广告
            $ads = $this->ad_dao->getAdList($condition);

            return $ads;
        });

        return $ads;
    }
}