<?php

/**
 * 相关辅助函数
 */

use myxland\extend\Exception;

if (! defined('NOW_TIME')) {
    define('NOW_TIME', $_SERVER['REQUEST_TIME']);
}

if (! function_exists('exception')) {

    function exception($msg, $code = -1)
    {
        throw new Exception($msg, $code);
    }
}

