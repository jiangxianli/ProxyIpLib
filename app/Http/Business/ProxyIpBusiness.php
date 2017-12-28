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
     * 抓取过程处理
     *
     * @param $urls
     * @param $table_selector
     * @param $map_func
     * @author jiangxianli
     * @created_at 2017-12-28 14:42:03
     */
    protected function grabProcess($urls, $table_selector, $map_func)
    {
        //遍历URL
        foreach ($urls as $url) {

            //记录抓取的URL
            $this->selfLogWriter($this->log_path, $url, true);
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
            //抓取网页内容
            $ql = QueryList::get($url, [], $options);
            //选中数据列表Table
            $table = $ql->find($table_selector);
            //遍历数据列
            $table->map(function ($tr) use ($map_func) {
                try {

                    //获取IP、端口、透明度、协议
                    list($ip, $port, $anonymity, $protocol) = call_user_func_array($map_func, [$tr]);
                    //日志记录
                    $log = sprintf("----%s://%s:%s------\n", $protocol, $ip, $port);
                    $this->selfLogWriter($this->log_path, $log, true);
                    //添加 代理IP
                    $this->addProxyIp($ip, $port, $protocol, $anonymity);
                } catch (JsonException $e) {
                    var_dump($e->formatError());
                    var_dump($e->getTraceAsString());
                } catch (\Exception $e) {
                    var_dump($e->getMessage());
                    var_dump($e->getTraceAsString());
                }
            });

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
            "http://www.kuaidaili.com/free/intr/",
        ];

        $this->grabProcess($urls, "#list table tbody tr", function ($tr) {
            $ip = $tr->find('td:eq(0)')->text();
            $port = $tr->find('td:eq(1)')->text();
            $anonymity = $tr->find('td:eq(2)')->text();
            $protocol = strtolower($tr->find('td:eq(3)')->text());
            return [$ip, $port, $anonymity, $protocol];
        });
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
            "http://www.xicidaili.com/nn/",
            "http://www.xicidaili.com/nt/",
            "http://www.xicidaili.com/wn/",
            "http://www.xicidaili.com/nt/",
        ];

        $this->grabProcess($urls, "table#ip_list tbody tr", function ($tr) {
            $ip = $tr->find('td:eq(1)')->text();
            $port = $tr->find('td:eq(2)')->text();
            $anonymity = $tr->find('td:eq(4)')->text() == "高匿" ? 2 : 1;
            $protocol = strtolower($tr->find('td:eq(5)')->text());
            return [$ip, $port, $anonymity, $protocol];
        });
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
            "http://www.goubanjia.com/index1.shtml",
            "http://www.goubanjia.com/index2.shtml",
            "http://www.goubanjia.com/index3.shtml",
        ];

        $this->grabProcess($urls, "#list table tbody tr", function ($tr) {
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
            return [$ip, $port, $anonymity, $protocol];
        });
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
            "http://www.66ip.cn/1.html",
            "http://www.66ip.cn/2.html",
            "http://www.66ip.cn/3.html",
        ];

        $this->grabProcess($urls, "#main table tbody tr", function ($tr) {
            $ip = $tr->find('td:eq(0)')->text();
            $port = $tr->find('td:eq(1)')->text();
            $anonymity = 2;
            $protocol = 'http';
            return [$ip, $port, $anonymity, $protocol];
        });

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
            "http://www.yun-daili.com/free.asp?stype=1&page=1",
            "http://www.yun-daili.com/free.asp?stype=2&page=1",
            "http://www.yun-daili.com/free.asp?stype=3&page=1",
            "http://www.yun-daili.com/free.asp?stype=4&page=1",
        ];

        $this->grabProcess($urls, "#main table tbody tr", function ($tr) {
            $ip = $tr->find('td:eq(0)')->text();
            $port = $tr->find('td:eq(1)')->text();
            $anonymity = 2;
            $protocol = $tr->find('td:eq(3)')->text() == "HTTP代理" ? "http" : "https";
            return [$ip, $port, $anonymity, $protocol];
        });

    }


    /**
     * Data5U
     *
     * @author jiangxianli
     * @created_at 2017-12-25 18:01:44
     */
    public function grabData5U()
    {
        $urls = [
            "http://www.data5u.com/free/index.shtml",
            "http://www.data5u.com/free/gngn/index.shtml",
            "http://www.data5u.com/free/gnpt/index.shtml",
            "http://www.data5u.com/free/gwgn/index.shtml",
            "http://www.data5u.com/free/gwpt/index.shtml"
        ];

        $this->grabProcess($urls, "ul.l2", function ($tr) {
            $ip = $tr->find('li:eq(0)')->text();
            $port = $tr->find('li:eq(1)')->text();
            $anonymity = $tr->find('li:eq(2)')->text() == '高匿' ? 2 : 1;
            $protocol = $tr->find('li:eq(3)')->text();
            return [$ip, $port, $anonymity, $protocol];
        });
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

        $urls = $this->getWebUrls();

        //代理请求
        $client = new Client();
        $client->request('GET', $urls[random_int(0, count($urls) - 1)], [
            'proxy'   => [
                $protocol => "tcp://$ip:$port"
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
            'proxy'   => [
                $protocol => "tcp://$ip:$port"
            ],
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
            try {
                $ip_cache_key = "IP_" . $proxy_ip->ip;
                $ip_location = \Cache::get($ip_cache_key);
                if (!$ip_location) {
                    $ip_location = $this->ipLocation($proxy_ip->ip);
                }
                $this->proxy_ip_dao->updateProxyIp($proxy_ip->unique_id, [
                    'isp'        => $ip_location['isp'],
                    'ip_address' => $ip_location['country'] . ' ' . $ip_location['region'] . ' ' . $ip_location['city']
                ]);
                \Cache::forever("IP_" . $proxy_ip->ip, $ip_location);
            } catch (JsonException $e) {
                var_dump($e->formatError());
                var_dump($e->getTraceAsString());
            } catch (\Exception $e) {
                var_dump($e->getMessage());
                var_dump($e->getTraceAsString());
            }
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
            "http://www.sohu.com/",
            "http://www.ifeng.com/",
            "https://mini.eastday.com/jrdftt/",
            "http://www.qq.com/",
            "http://news.2345.com/shouye/",
            "https://www.baidu.com/",
            "https://weibo.com/",
            "http://tejia.aili.com/",
            "http://www.163.com/",
            "https://www.jd.com",
            "https://www.taobao.com/",
            "https://www.autohome.com.cn/",
            "http://www.eastmoney.com/",
            "https://www.yhd.com/",
            "http://game.2345.com/",
            "https://www.qunar.com/",
            "http://email.163.com/",
            "http://www.youku.com/",
            "https://www.qidian.com/",
            "http://www.xxsy.net/",
            "http://www.sznews.com/",
            "http://www.sun0769.com/",
            "http://www.nandu.ai/",
            "http://www.southcn.com/",
            "http://www.foshannews.net/",
            "http://www.21cn.com/",
            "http://www.020.com/",
            "http://www.dayoo.com/",
            "http://www.meizhou.cn/",
            "http://www.infzm.com/",
            "http://www.southcn.com/",
            "http://www.gdtv.cn/",
            "https://www.douban.com/",
            "https://www.tianyancha.com/",
            "https://open.163.com/",
            "http://lady.163.com/",
            "http://www.10jqka.com.cn/",
            "http://guangzhou.baixing.com/",
            "http://www.xiaozhu.com/",
            "http://www.jiayuan.com/",
            "http://mobile.pconline.com.cn/",
            "https://www.ithome.com/",
            "https://www.mi.com/",
            "http://www.tianya.cn/",
            "http://www.xiaohongshu.com/",
            "http://www.tiexue.net/",
            "http://mil.huanqiu.com/",
            "http://gz.fang.com/",
            "http://www.dangdang.com/",
            "http://www.elong.com/",
            "https://www.kaola.com/",
            "https://www.qidian.com/",
            "http://www.zongheng.com/",
            "https://www.hongxiu.com/",
            "http://36kr.com/",
            "https://www.duitang.com/",
            "https://www.guokr.com/",
            "https://www.huxiu.com/",
            "https://www.iwjw.com/"
        ];
    }
}