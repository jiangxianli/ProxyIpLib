<?php

namespace App\Http\Business\Spider;

use App\Jobs\GrabProxyIp;
use Symfony\Component\Finder\Finder;

class CronSpider
{
    /**
     * @throws \ReflectionException
     * @author jiangxianli
     * @created_at 2021-03-01 16:53
     */
    public function cronTimer()
    {
        $this->tableSpiderCron();
        $this->htmlSpiderCron();
    }

    /**
     * @throws \ReflectionException
     * @author jiangxianli
     * @created_at 2021-03-01 16:50
     */
    public function tableSpiderCron()
    {
        foreach (Finder::create()->files()->name('*.php')->in(base_path('app/Http/Business/Spider/Table')) as $file) {
            $class_name = __NAMESPACE__ . '\Table\\' . substr($file->getFileName(), 0, -4);
            $class = new \ReflectionClass($class_name);
            $instance = app($class->getName());
            if ($instance->is_open) {
                dispatch(new GrabProxyIp($instance));
            }
        }
    }

    /**
     * @throws \ReflectionException
     * @author jiangxianli
     * @created_at 2021-03-01 16:50
     */
    public function htmlSpiderCron()
    {
        foreach (Finder::create()->files()->name('*.php')->in(base_path('app/Http/Business/Spider/Html')) as $file) {
            $class_name = __NAMESPACE__ . '\Html\\' . substr($file->getFileName(), 0, -4);
            $class = new \ReflectionClass($class_name);
            $instance = app($class->getName());
            if ($instance->is_open) {
                dispatch(new GrabProxyIp($instance));
            }
        }
    }
}