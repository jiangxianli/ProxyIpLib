<?php
/**
 * Created by PhpStorm.
 * User: Jaeger <JaegerCode@gmail.com>
 * Date: 18/12/11
 * Time: 下午6:48
 */

require __DIR__.'/../vendor/autoload.php';
use Jaeger\GHttp;
use Cache\Adapter\Predis\PredisCachePool;


$rt = GHttp::get('http://httpbin.org/get',[
    'wd' => 'QueryList'
],[
    'cache' => __DIR__,
    'cache_ttl' => 120
]);

print_r($rt);

$client = new \Predis\Client('tcp:/127.0.0.1:6379');
$pool = new PredisCachePool($client);

$rt = GHttp::get('http://httpbin.org/get',[
    'wd' => 'QueryList'
],[
    'cache' => $pool,
    'cache_ttl' => 120
]);

print_r($rt);