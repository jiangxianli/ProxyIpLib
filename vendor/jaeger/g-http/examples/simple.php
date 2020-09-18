<?php
/**
 * Created by PhpStorm.
 * User: Jaeger <JaegerCode@gmail.com>
 * Date: 18/12/11
 * Time: 下午6:48
 */

require __DIR__.'/../vendor/autoload.php';
use Jaeger\GHttp;

$rt = GHttp::get('http://httpbin.org/get',[
    'wd' => 'QueryList'
],[
    'headers' => [
        'referer' => 'https://baidu.com',
        'User-Agent' => 'Mozilla/5.0 (Windows NTChrome/58.0.3029.110 Safari/537.36',
        'Cookie' => 'cookie xxx'
    ],
]);

print_r($rt);
