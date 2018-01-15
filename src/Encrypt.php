<?php

/**
 * 可逆加密解密类
 */

namespace myxland\extend;

class Encrypt
{
    private static function getKey()
    {
        return config('encrypt_key');
    }

    /**
     * 加密字符串
     *
     * @param string $data 字符串/数组
     * @param integer $expire 有效期（秒）
     * @param string $key 加密key
     * @return string
     */
    public static function encrypt($data, $expire = 0, $key = '')
    {
        if (is_array($data)) {
            $data = json_encode($data, JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
        }
        $expire = sprintf('%010d', $expire ? $expire + time() : 0);
        if (! $key) {
            $key = self::getKey();
        }
        $key  = md5($key);
        $data = base64_encode($expire . $data);
        $x    = 0;
        $len  = strlen($data);
        $l    = strlen($key);
        $char = $str = '';

        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= substr($key, $x, 1);
            $x++;
        }

        for ($i = 0; $i < $len; $i++) {
            $str .= chr(ord(substr($data, $i, 1)) + (ord(substr($char, $i, 1))) % 256);
        }

        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($str));
    }

    /**
     * 解密字符串
     *
     * @param string $data 字符串
     * @param string $key 加密key
     * @return string
     */
    public static function decrypt($data, $key = '')
    {
        if (! $data) {
            return '';
        }
        if (! $key) {
            $key = self::getKey();
        }
        $key  = md5($key);
        $data = str_replace(['-', '_'], ['+', '/'], $data);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        $data = base64_decode($data);

        $x    = 0;
        $len  = strlen($data);
        $l    = strlen($key);
        $char = $str = '';

        for ($i = 0; $i < $len; $i++) {
            if ($x == $l) {
                $x = 0;
            }
            $char .= substr($key, $x, 1);
            $x++;
        }

        for ($i = 0; $i < $len; $i++) {
            if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            } else {
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        $data   = base64_decode($str);
        $expire = substr($data, 0, 10);
        if ($expire > 0 && $expire < time()) {
            return '';
        }
        $data = substr($data, 10);
        $json = json_decode($data, true);

        return json_last_error() === JSON_ERROR_NONE ? $json : $data;
    }

    /**
     * MD5(MD5(key), salt)加密字符串
     *
     * @param string $data 字符串/数组
     * @param string $key 加密key
     * @return string
     */
    public static function encrypt_md5($data, $key = '')
    {
        if (! $data) {
            return '';
        }
        if (! $key) {
            $key = self::getKey();
        }

        return md5($key . md5($data));
    }
}
