<?php
/**
 * Created by PhpStorm.
 * User: Jaeger <JaegerCode@gmail.com>
 * Date: 18/12/10
 * Time: 下午6:51
 */

require __DIR__.'/../vendor/autoload.php';
use Jaeger\GHttp;
use GuzzleHttp\Psr7\Request;

$requests = [
    new Request('POST','http://httpbin.org/post',[
        'Content-Type' => 'application/x-www-form-urlencoded',
        'User-Agent' => 'g-http'
    ],http_build_query([
        'name' => 'php'
    ])),
    new Request('POST','http://httpbin.org/post',[
        'Content-Type' => 'application/x-www-form-urlencoded',
        'User-Agent' => 'g-http'
    ],http_build_query([
        'name' => 'go'
    ])),
    new Request('POST','http://httpbin.org/post',[
        'Content-Type' => 'application/x-www-form-urlencoded',
        'User-Agent' => 'g-http'
    ],http_build_query([
        'name' => 'c#'
    ]))
];

GHttp::multiRequest($requests)->success(function($response,$index){
    print_r((String)$response->getBody());
    print_r($index);
})->post();