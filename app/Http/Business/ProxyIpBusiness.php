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
            "http://www.kuaidaili.com/free/inha/%s/",
            "http://www.kuaidaili.com/free/intr/%s/"
        ];
        foreach ($urls as $url) {
            for ($page = 1; $page <= 100; $page++) {
                $this->selfLogWriter($this->log_path, sprintf($url, $page), true);
                $ql = QueryList::get(sprintf($url, $page), [], [
                    'headers' => [
                        'Referer'                   => "http://www.kuaidaili.com/free/inha/1982/",
                        'User-Agent'                => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.3 Safari/537.36",
                        'Accept'                    => "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
                        'Upgrade-Insecure-Requests' => "1",
                        'Host'                      => "www.kuaidaili.com",
                        'DNT'                       => "1",
                    ],
                    'timeout' => $this->time_out
                ]);

                $table = $ql->find('#list table tbody tr');

                $table->map(function ($tr) {
                    try {
                        $ip = $tr->find('td:eq(0)')->text();
                        $port = $tr->find('td:eq(1)')->text();
                        $anonymity = $tr->find('td:eq(2)')->text();
                        $protocol = strtolower($tr->find('td:eq(3)')->text());

                        $log = sprintf("----%s://%s:%s------\n", $protocol, $ip, $port);
                        $this->selfLogWriter($this->log_path, $log, true);
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
            "http://www.xicidaili.com/nn/%d", //国内高匿代理
        ];
        foreach ($urls as $url) {
            for ($page = 1; $page <= 100; $page++) {
                $this->selfLogWriter($this->log_path, sprintf($url, $page), true);
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
                    'timeout' => $this->time_out
                ]);

                $table = $ql->find('table#ip_list tbody tr');

                $table->map(function ($tr) {
                    try {
                        $ip = $tr->find('td:eq(1)')->text();
                        $port = $tr->find('td:eq(2)')->text();
                        $anonymity = $tr->find('td:eq(4)')->text() == "高匿" ? 2 : 1;
                        $protocol = strtolower($tr->find('td:eq(5)')->text());

                        $log = sprintf("----%s://%s:%s------\n", $protocol, $ip, $port);
                        $this->selfLogWriter($this->log_path, $log, true);
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
     * GouBanJia
     *
     * @author jiangxianli
     * @created_at 2017-12-25 16:34:02
     */
    public function grabGouBanJia()
    {
        $urls = [
            "http://www.goubanjia.com/index%d.shtml", //国内高匿代理
        ];
        foreach ($urls as $url) {
            for ($page = 1; $page <= 10; $page++) {
                $this->selfLogWriter($this->log_path, sprintf($url, $page), true);
                $ql = QueryList::get(sprintf($url, $page), [], [
                    'headers' => [
                        "If-None-Match"             => "W/\"6bcd47cf01c3cbee554285d35201bdd5\"",
                        'Referer'                   => "http://www.goubanjia.com/",
                        'User-Agent'                => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.3 Safari/537.36",
                        'Accept'                    => "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
                        'Upgrade-Insecure-Requests' => "1",
                        'Host'                      => "www.goubanjia.com",
                        'DNT'                       => "1",
                    ],
                    'timeout' => $this->time_out
                ]);

                $table = $ql->find('#list table tbody tr');

                $table->map(function ($tr) {
                    try {
                        $ip_port = "";
                        $tr->find('td:eq(0)')->children()->map(function ($item) use (&$ip_port) {
                            if (!str_contains($item->attr('style'), ["none"]) && !$item->hasClass('port')) {
                                $ip_port .= $item->text();
                            }
                        });
                        $ip = str_replace("..", ".", $ip_port);
                        $port = $tr->find('td:eq(0) .port:eq(0)')->text();
                        $anonymity = $tr->find('td:eq(1) a:eq(0)')->text() == "高匿" ? 2 : 1;
                        $protocol = strtolower($tr->find('td:eq(2) a:eq(0)')->text());

                        $log = sprintf("----%s://%s:%s------\n", $protocol, $ip, $port);
                        $this->selfLogWriter($this->log_path, $log, true);
                        $this->addProxyIp($ip, $port, $protocol, $anonymity);
                    } catch (\Exception $e) {
                        var_dump($e instanceof JsonException ? $e->formatError() : $e->getMessage());
                        var_dump($e->getTraceAsString());
                    }
                });

                sleep(10);
            }
        }
    }


    /**
     * SixSixIp
     *
     * @author jiangxianli
     * @created_at 2017-12-25 16:34:02
     */
    public function grabSixSixIp()
    {
        $urls = [
            "http://www.66ip.cn/%d.html", //国内高匿代理
        ];
        foreach ($urls as $url) {
            for ($page = 1; $page <= 20; $page++) {
                $this->selfLogWriter($this->log_path, sprintf($url, $page), true);
                $ql = QueryList::get(sprintf($url, $page), [], [
                    'headers' => [
                        "If-None-Match"             => "W/\"6bcd47cf01c3cbee554285d35201bdd5\"",
                        'Referer'                   => "http://www.66ip.cn/",
                        'User-Agent'                => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.3 Safari/537.36",
                        'Accept'                    => "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
                        'Upgrade-Insecure-Requests' => "1",
                        'Host'                      => "www.66ip.cn",
                        'DNT'                       => "1",
                    ],
                    'timeout' => $this->time_out
                ]);

                $table = $ql->find('#main table tbody tr');

                $table->map(function ($tr) {
                    try {

                        $ip = $tr->find('td:eq(0)')->text();
                        $port = $tr->find('td:eq(1)')->text();
                        $anonymity = 2;
                        $protocol = 'http';

                        $log = sprintf("----%s://%s:%s------\n", $protocol, $ip, $port);
                        $this->selfLogWriter($this->log_path, $log, true);
                        $this->addProxyIp($ip, $port, $protocol, $anonymity);
                    } catch (\Exception $e) {
                        var_dump($e instanceof JsonException ? $e->formatError() : $e->getMessage());
                        var_dump($e->getTraceAsString());
                    }
                });

                sleep(10);
            }
        }
    }

    /**
     * 云代理
     *
     * @author jiangxianli
     * @created_at 2017-12-25 18:01:44
     */
    public function grabYunDaiLi()
    {
        $urls = [
            "http://www.yun-daili.com/free.asp?page=%d", //国内高匿代理
        ];
        foreach ($urls as $url) {
            for ($page = 1; $page <= 15; $page++) {
                $this->selfLogWriter($this->log_path, sprintf($url, $page), true);
                $ql = QueryList::get(sprintf($url, $page), [], [
                    'headers' => [
                        "If-None-Match"             => "W/\"6bcd47cf01c3cbee554285d35201bdd5\"",
                        'Referer'                   => "http://www.yun-daili.com/",
                        'User-Agent'                => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.3 Safari/537.36",
                        'Accept'                    => "text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8",
                        'Upgrade-Insecure-Requests' => "1",
                        'Host'                      => "www.yun-daili.com",
                        'DNT'                       => "1",
                    ],
                    'timeout' => $this->time_out
                ]);

                $table = $ql->find('#main table tbody tr');

                $table->map(function ($tr) {
                    try {

                        $ip = $tr->find('td:eq(0)')->text();
                        $port = $tr->find('td:eq(1)')->text();
                        $anonymity = 2;
                        $protocol = $tr->find('td:eq(3)')->text() == "HTTP代理" ? "http" : "https";

                        $log = sprintf("----%s://%s:%s------\n", $protocol, $ip, $port);
                        $this->selfLogWriter($this->log_path, $log, true);
                        $this->addProxyIp($ip, $port, $protocol, $anonymity);
                    } catch (\Exception $e) {
                        var_dump($e instanceof JsonException ? $e->formatError() : $e->getMessage());
                        var_dump($e->getTraceAsString());
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
                var_dump($exception->getMessage());
                var_dump($exception->getTraceAsString());
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
        $ip_address = $ip_location['country'] .$ip_location['region'] . ' ' . $ip_location['city'];
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
        $log = json_encode($proxy_ip->toArray());
        $this->selfLogWriter($this->log_path, $log, true);
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
            'timeout' => $this->time_out
        ]);

        $end_seconds = Helper::mSecondTime();

        return intval($end_seconds - $begin_seconds);
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
     * 自定义日志记录
     *
     * @param $dir_name
     * @param $log
     * @param bool $console
     * @param int $days
     * @author jiangxianli
     * @created_at 2017-11-10 17:13:01
     */
    public function selfLogWriter($dir_name, $log, $console = false, $days = 1)
    {
        //总目录
        $root_dir = storage_path("logs/" . $dir_name . "/");
        //按日存放
        $day_dir = str_replace("//", "/", $root_dir . "/" . date('Y-m-d'));
        //日志路径
        $hour_log_path = str_replace("//", "/", $day_dir . '.log');
        //目录路径
        $hour_dir = dirname($hour_log_path);
        //检测目录，创建目录
        if (!file_exists($hour_dir)) {
            mkdir($hour_dir, 0755, true);
        }

        //写入日志
        $log = '[' . date('Y-m-d H:i:s') . '] - ' . $log . "\n";
        $log = str_replace("\n\n", "\n", $log);
        file_put_contents($hour_log_path, $log, FILE_APPEND);

        if ($console) {
            echo $log;
        }

        try {
            exec('find ' . $root_dir . ' -mtime ' . $days . ' | xargs rm -rf ');
        } catch (\Exception $e) {

        }
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
            'limit'      => 100,
            'order_by'   => 'validated_at',
            'order_rule' => 'desc'
        ];
        $proxy_ips = $this->proxy_ip_dao->getProxyIpList($condition);
        foreach ($proxy_ips as $proxy_ip) {
            try {
                $this->ipSpeedCheck($proxy_ip->ip, $proxy_ip->port, $proxy_ip->protocol);

                return $proxy_ip;

            } catch (\Exception $exception) {

            }
        }
    }
}