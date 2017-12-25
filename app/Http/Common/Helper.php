<?php

namespace App\Http\Common;

class Helper
{
    /**
     * 获取当前毫秒
     *
     * @return float
     * @author jiangxianli
     * @created_at 2017-12-22 16:47:28
     */
    public static function mSecondTime()
    {
        list($m_seconds, $seconds) = explode(' ', microtime());
        return (float)sprintf('%.0f', (floatval($m_seconds) + floatval($seconds)) * 1000);
    }

    /**
     * 生成唯一ID
     *
     * @return string
     * @author jiangxianli
     * @created_at 2017-12-22 16:53:24
     */
    public static function unique_id()
    {
        return md5(uniqid());
    }

}
