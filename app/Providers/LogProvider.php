<?php

namespace App\Providers;

use App\Http\Common\Helper;
use Illuminate\Support\ServiceProvider;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class LogProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Logger', function ($app) {

            $logger = new Logger(Helper::logFlag());

            $logger->pushProcessor(new \Monolog\Processor\WebProcessor);
            $logger->pushProcessor(new \Monolog\Processor\PsrLogMessageProcessor);

            $logger->pushHandler(new RotatingFileHandler(storage_path('logs/logger.log'), 5, Logger::DEBUG));

            return $logger;
        });
    }

    /**
     * @return array
     * @author jiangxianli
     * @created_at 2019-10-23 15:24
     */
    public function provides()
    {
        return [
            'Logger'
        ];
    }
}
