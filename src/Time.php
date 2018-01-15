<?php

/**
 * 时间处理类
 */

namespace myxland\extend;

class Time
{
    public static function optimization($time)
    {
        $return = null;
        if (! is_numeric($time)) {
            $time = strtotime($time);
        }
        $d1    = date('d', $time);
        $d2    = date('d', NOW_TIME);
        $y1    = date('Y', $time);
        $y2    = date('Y', NOW_TIME);
        $hTime = date('H:i', $time);
        $dif   = abs(NOW_TIME - $time);
        if ($dif < 10) {
            $return = '刚刚';
        } else {
            if ($dif < 3600) {
                $return = floor($dif / 60) . '分钟前';
            } else {
                if ($dif < 10800) {
                    $return = floor($dif / 3600) . '小时前';
                } else {
                    if ($d1 == $d2) {
                        $return = '今天 ' . $hTime;
                    } else {
                        if ($dif < 86400) {
                            $return = '昨天 ' . $hTime;
                        } else {
                            if ($dif < 172800) {
                                $return = '前天 ' . $hTime;
                            } else {
                                if ($y1 == $y2) {
                                    $return = date('m-d H:i', $time);
                                } else {
                                    $return = date('Y-m-d H:i', $time);
                                }
                            }
                        }
                    }
                }
            }
        }

        return $return;
    }
}
