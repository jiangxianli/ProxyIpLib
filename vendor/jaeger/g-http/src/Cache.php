<?php
/**
 * Created by PhpStorm.
 * User: Jaeger <JaegerCode@gmail.com>
 * Date: 18/12/11
 * Time: 下午6:39
 */

namespace Jaeger;


use Cache\Adapter\Common\AbstractCachePool;
use Cache\Adapter\Filesystem\FilesystemCachePool;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;

class Cache extends GHttp
{
    public static function remember($name, $arguments)
    {
        $cachePool = null;
        $cacheConfig = self::initCacheConfig($arguments);

        if (empty($cacheConfig['cache'])) {
            return self::$name(...$arguments);
        }
        if (is_string($cacheConfig['cache'])) {
            $filesystemAdapter = new Local($cacheConfig['cache']);
            $filesystem        = new Filesystem($filesystemAdapter);
            $cachePool = new FilesystemCachePool($filesystem);
        }else if ($cacheConfig['cache'] instanceof AbstractCachePool) {
            $cachePool = $cacheConfig['cache'];
        }

        $cacheKey = self::getCacheKey($name,$arguments);
        $data = $cachePool->get($cacheKey);
        if(empty($data)) {
            $data = self::$name(...$arguments);
            if(!empty($data)) {
                $cachePool->set($cacheKey,$data,$cacheConfig['cache_ttl']);
            }
        }
        return $data;
    }

    protected static function initCacheConfig($arguments)
    {
        $cacheConfig = [
            'cache' => null,
            'cache_ttl' => null
        ];
        if(!empty($arguments[2])) {
            $cacheConfig = array_merge([
                'cache' => null,
                'cache_ttl' => null
            ],$arguments[2]);
        }
        return $cacheConfig;
    }

    protected static function getCacheKey($name, $arguments)
    {
        return md5($name.'_'.json_encode($arguments));
    }
}