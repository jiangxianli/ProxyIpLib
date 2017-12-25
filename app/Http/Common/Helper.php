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

    /**
     * 格式化显示
     *
     * @param $anonymity
     * @return string
     * @author jiangxianli
     * @created_at 2017-12-25 14:09:43
     */
    public static function formatAnonymity($anonymity)
    {
        switch ($anonymity) {
            case 0:
                return '普通';
            case 1:
                return '透明';
            case 2:
                return '高匿';
            default:
                return '未知';
        }
    }

    /**
     * 格式化显示响应速度
     *
     * @param $speed
     * @return string
     * @author jiangxianli
     * @created_at 2017-12-25 14:16:48
     */
    public static function formatSpeed($speed)
    {
        if ($speed <= 500) {
            return $speed . '毫秒';
        }
        return printf("%.2f", ($speed / 1000)) . '秒';
    }

}
