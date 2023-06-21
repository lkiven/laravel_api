<?php

use \Zhuzhichao\IpLocationZh\Ip;

/**ip地址转化为具体位置
 * @param $ip  ip
 * @param $isString  是否连接味字符串
 * @param $linkString  连接字符串
 * @return mixed|string
 */
function ip_change_location($ip,$isString=false,$linkString='-')
{
    //默认返回结果array (size=4)
    //  0 => string '中国' (length=6)
    //  1 => string '河南' (length=6)
    //  2 => string '郑州' (length=6)
    //  3 => string '' (length=0)
    //  4 => string '410100' (length=6)
    $ipLocation = Ip::find($ip);
    //判断是否需要连接成为字符串
    if($isString){
        return rtrim(implode($linkString,$ipLocation),$linkString);
    }
    return $ipLocation;
}


/**格式化时间
 * @param string $time 时间戳
 * @param string $format 格式化时间格式
 * @return false|string
 */
function date_customize_format($time='',$format='Y-m-d H:i:s')
{
    $time = $time ? $time : time();
    return date($format,$time);
}

