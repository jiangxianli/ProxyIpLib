<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', ['as' => 'web.index', 'uses' => 'ProxyIpController@index']);

$router->get('/blog.html', ['as' => 'blog.index', 'uses' => 'BlogController@index']);

$router->get('/blog/{blog_id:[0-9]+}.html', ['as' => 'blog.detail', 'uses' => 'BlogController@detail']);

$router->get('sitemap.xml', ['as' => 'sitemap.index', 'uses' => 'BlogController@siteMapXml']);
$router->get('sitemap.txt', ['as' => 'sitemap.txt', 'uses' => 'BlogController@siteMapTxt']);

