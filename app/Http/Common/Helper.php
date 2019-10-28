<?php

namespace App\Http\Common;

use Carbon\Carbon;

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

    /**
     * 日志标识
     *
     * @param $flag_name
     * @return string
     * @author jiangxianli
     * @created_at 2019-10-23 16:20
     */
    public static function logFlag($flag_name = "")
    {
        static $flag = '';

        if (!empty($flag_name)) {
            $flag = $flag_name;
        }

        return $flag;
    }

    /**
     * 时间格式化显示
     *
     * @param $time
     * @return string
     * @author jiangxianli
     * @created_at 2019-10-28 16:31
     */
    public static function formatDateDay($time)
    {
        $distance = Carbon::now()->diffInSeconds($time);

        //天
        $day = intval($distance / 86400);
        //小时
        $hour = ($distance % 86400) > 0 ? intval(($distance % 86400) / 3600) : 0;
        //
        $minutes = ($distance % 3600) > 0 ? intval(($distance % 3600) / 60) : 0;
        //秒
        $seconds = $distance % 60;

        if ($day > 0) {
            return $day . "天" . $hour . "小时";
        } else if ($hour > 0) {
            return $hour . "小时" . $minutes . "分钟";
        } else if ($minutes > 0) {
            return $minutes . "分钟" . $seconds . "秒";
        } else {
            return $seconds . "秒";
        }
    }

}
