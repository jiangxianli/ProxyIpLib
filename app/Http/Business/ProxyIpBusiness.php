<?php

namespace App\Http\Business;

use App\Exceptions\JsonException;
use App\Http\Business\Dao\ProxyIpDao;
use App\Http\Common\Helper;
use Carbon\Carbon;
use GuzzleHttp\Client;
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
     * 构造函数
     *
     * ProxyIpBusiness constructor.
     * @param ProxyIpDao $proxy_ip_dao
     */
    public function __construct(ProxyIpDao $proxy_ip_dao)
    {
        $this->proxy_ip_dao = $proxy_ip_dao;
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
            "http://www.kuaidaili.com/free/inha/%s/"
        ];
        foreach ($urls as $url) {
            for ($page = 1; $page <= 100; $page++) {
                //URL
                $url = sprintf($url, $page);
                echo $url . "\n";
                //抓取页面
                $ql = QueryList::get($url, [], [
                    'headers' => [
                        'Referer'                   => "http://www.kuaidaili.com/free/inha/1982/",
                        'User-Agent'                => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.3 Safari/537.36",
                        'Accept'                    => "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
                        'Upgrade-Insecure-Requests' => "1",
                        'Host'                      => "www.kuaidaili.com",
                        'DNT'                       => "1",
                        'Cookie'                    => "_gat=1; channelid=0; sid=1513932309043947; _ga=GA1.2.660797916.1513928579; _gid=GA1.2.1943992233.1513928579; Hm_lvt_7ed65b1cc4b810e9fd37959c9bb51b31=1513928580; Hm_lpvt_7ed65b1cc4b810e9fd37959c9bb51b31=1513932985"
                    ],
                    'timeout' => 8
                ]);

                $table = $ql->find('#list table tbody tr');

                $table->map(function ($tr) {
                    try {
                        $ip = $tr->find('td:eq(0)')->text();
                        $port = $tr->find('td:eq(1)')->text();
                        $anonymity = $tr->find('td:eq(2)')->text();
                        $protocol = strtolower($tr->find('td:eq(3)')->text());

                        echo sprintf("----%s://%s:%s------\n", $protocol, $ip, $port);
                        $this->addProxyIp($ip, $port, $protocol, $anonymity);
                    } catch (\Exception $e) {
                        var_dump($e->getMessage());
                        var_dump($e->getTraceAsString());
                    }
                });

                sleep(10);
            }
        }

    }

    /**
     * 西刺免费代理
     *
     * @author jiangxianli
     * @created_at
     */
    public function grabXiCiDaiLi()
    {
        $urls = [
            'http://www.xicidaili.com/nn/%s', //国内高匿代理
        ];
        foreach ($urls as $url) {
            for ($page = 1; $page <= 100; $page++) {
                echo sprintf($url, $page) . "\n";
                $ql = QueryList::get(sprintf($url, $page), [], [
                    'headers' => [
                        "If-None-Match"             => "W/\"6bcd47cf01c3cbee554285d35201bdd5\"",
                        'Referer'                   => "http://www.xicidaili.com/",
                        'User-Agent'                => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.3 Safari/537.36",
                        'Accept'                    => "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
                        'Upgrade-Insecure-Requests' => "1",
                        'Host'                      => "www.xicidaili.com",
                        'DNT'                       => "1",
                    ],
                    'timeout' => 8
                ]);

                $table = $ql->find('table#ip_list tbody tr');

                $table->map(function ($tr) {
                    try {
                        $ip = $tr->find('td:eq(1)')->text();
                        $port = $tr->find('td:eq(2)')->text();
                        $anonymity = $tr->find('td:eq(4)')->text() == "高匿" ? 2 : 1;
                        $protocol = strtolower($tr->find('td:eq(5)')->text());

                        echo sprintf("----%s://%s:%s------\n", $protocol, $ip, $port);
                        $this->addProxyIp($ip, $port, $protocol, $anonymity);
                    } catch (\Exception $e) {
                        var_dump($e->getMessage());
                    }
                });

                sleep(10);
            }
        }
    }

    /**
     * 定时清理
     *
     * @throws JsonException
     * @author jiangxianli
     * @created_at 2017-12-25 10:38:13
     */
    public function timerClearProxyIp()
    {
        $condition = [
            'all' => 'true'
        ];
        $proxy_ips = $this->proxy_ip_dao->getProxyIpList($condition);

        foreach ($proxy_ips as $proxy_ip) {

            try {
                $speed = $this->ipSpeedCheck($proxy_ip->ip, $proxy_ip->port, $proxy_ip->protocol);
                $this->proxy_ip_dao->updateProxyIp($proxy_ip->unique_id, [
                    'speed'        => $speed,
                    'validated_at' => Carbon::now(),
                ]);
            } catch (\Exception $exception) {
                $this->proxy_ip_dao->deleteProxyIp($proxy_ip->unique_id);
            }
        }
    }

    /**
     * 添加代理IP
     *
     * @param $ip
     * @param $port
     * @param $protocol
     * @param $anonymity
     * @throws JsonException
     * @author jiangxianli
     * @created_at 2017-12-22 16:52:09
     */
    protected function addProxyIp($ip, $port, $protocol, $anonymity)
    {
        //查询IP唯一性
        $proxy_ip = $this->proxy_ip_dao->findUniqueProxyIp($ip, $port, $protocol);
        if ($proxy_ip) {
            return;
        }

        //IP地址定位
        $ip_location = $this->ipLocation($ip);
        //IP 物理定位地址
        $ip_address = $ip_location['region'] . ' ' . $ip_location['city'];
        //ISP
        $isp = $ip_location['isp'];
        //响应速度
        $speed = $this->ipSpeedCheck($ip, $port, $protocol);

        $ip_data = [
            'unique_id'    => Helper::unique_id(),
            'ip'           => $ip,
            'port'         => $port,
            'anonymity'    => $anonymity,
            'protocol'     => $protocol,
            'speed'        => $speed,
            'isp'          => $isp,
            'ip_address'   => $ip_address,
            'validated_at' => Carbon::now(),
        ];
        $proxy_ip = $this->proxy_ip_dao->addProxyIp($ip_data);
        var_dump($proxy_ip->toArray());
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
    protected function ipLocation($ip)
    {
        //API 地址
        $api = "http://ip.taobao.com/service/getIpInfo.php?ip=" . $ip;
        //响应json数据
        $json = file_get_contents($api);
        //转数组格式
        $data = (array)json_decode($json, true);

        if (!isset($data['code']) || $data['code'] != 0) {
            throw new JsonException(90000, $data);
        }

        return $data['data'];
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
    protected function ipSpeedCheck($ip, $port, $protocol)
    {
        //开始请求毫秒
        $begin_seconds = Helper::mSecondTime();

        $urls = [
            "https://segmentfault.com/",
            "https://www.baidu.com/",
            "http://ping.chinaz.com/",
            "http://www.jb51.net",
            "http://www.sina.com.cn/",
            "https://www.360.cn/",
            "https://im.qq.com/"
        ];

        //代理请求
        $client = new Client();
        $client->request('GET', $urls[random_int(0, count($urls) - 1)], [
            'proxy'   => [
                $protocol => "$protocol://$ip:$port"
            ],
            'timeout' => 5
        ]);

        $end_seconds = Helper::mSecondTime();

        return intval($end_seconds - $begin_seconds);
    }
}