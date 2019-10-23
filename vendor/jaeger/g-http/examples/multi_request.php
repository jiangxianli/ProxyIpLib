<?php
/**
 * Created by PhpStorm.
 * User: Jaeger <JaegerCode@gmail.com>
 * Date: 18/12/10
 * Time: 下午4:04
 */
require __DIR__.'/../vendor/autoload.php';
use Jaeger\GHttp;

$urls = [
    'http://httpbin.org/get?name=php',
    'http://httpbin.org/get?name=go',
    'http://httpbin.org/get?name=c#',
    'http://httpbin.org/get?name=java'
];

GHttp::multiRequest($urls)->withHeaders([
    'X-Powered-By' => 'Jaeger'
])->withOptions([
    'timeout' => 10
])->concurrency(2)->success(function($response,$index){
    print_r((String)$response->getBody());
    print_r($index);
})->error(function($reason,$index){
    print_r($reason);
})->get();