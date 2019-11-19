<?php

namespace App\Providers;

use App\Model\Blog;
use App\Model\ProxyIp;
use Illuminate\Support\ServiceProvider;

class ModelServiceProvider extends ServiceProvider
{

    /**
     * 是否延时加载
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * 服务初始化
     *
     * @author jiangxianli
     * @created_at 2017-12-22 16:01:49
     */
    public function boot()
    {
        //
    }

    /**
     * 注册应用服务
     *
     * @author jiangxianli
     * @created_at 2017-12-22 16:00:02
     */
    public function register()
    {
        //代理IP模型
        $this->app->bind('ProxyIpModel', ProxyIp::class);
        //文章模型
        $this->app->bind('BlogModel', Blog::class);
    }


    /**
     * 模型 提供者
     *
     * @return array
     * @author jiangxianli
     * @created_at 2017-12-22 16:00:58
     */
    public function provides()
    {
        return [
            'ProxyIpModel',
            'BlogModel',
        ];
    }

}
