<?php

use \Zhuzhichao\IpLocationZh\Ip;
use itbdw\Ip\IpLocation;
use Illuminate\Support\Facades\DB;

/**ip地址转化为具体位置（扩展包：zhuzhichao/ip-location-zh）
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


/**ip地址转化为具体位置（扩展包：itbdw/ip-database）
 * @param $ip  ip
 * @param $tye  只能是'ip', 'country','province','city','county','area','isp'
 * @return mixed|string
 */
function ip_get_location( string $ip, array $tye = ['area'])
{
    //{"ip":"221.196.0.0","country":"中国","province":"天津","city":"河北区","county":"","area":"中国天津河北区 联通","isp":"联通"}
    $location  = IpLocation::getLocation($ip);

    //默认返回area
    if($tye == ['area']){
        return $location['area'] ?? '未知';
    }
    //判断参数是否只能在这里面
   if(collect($tye)->diff(['ip', 'country','province','city','county','area','isp'])){
       return  false;
   }
   $data = [];
   foreach ($tye as $value){
       $data[$value] = $location[$value] ?? '';
   }
   return $data;
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



/**
 * $where = [ 'id' => [180, 181, 182, 183], 'user_id' => [5, 15, 11, 1]];
 * $needUpdateFields = [ 'view_count' => [11, 22, 33, 44], 'updated_at' => ['2019-11-06 06:44:58', '2019-11-30 19:59:34', '2019-11-05 11:58:41', '2019-12-13 01:27:59']];
 *
 * 最终执行的 sql 语句如下所示
 *
 * UPDATE articles SET
 * view_count = CASE
 * WHEN id = 183 AND user_id = 1 THEN 44
 * WHEN id = 182 AND user_id = 11 THEN 33
 * WHEN id = 181 AND user_id = 15 THEN 22
 * WHEN id = 180 AND user_id = 5 THEN 11
 * ELSE view_count END,
 * updated_at = CASE
 * WHEN id = 183 AND user_id = 1 THEN '2019-12-13 01:27:59'
 * WHEN id = 182 AND user_id = 11 THEN '2019-11-05 11:58:41'
 * WHEN id = 181 AND user_id = 15 THEN '2019-11-30 19:59:34'
 * WHEN id = 180 AND user_id = 5 THEN '2019-11-06 06:44:58'
 * ELSE updated_at END
 *
 *
 * 批量更新数据
 *
 * @param string $tableName  需要更新的表名称
 * @param array $where  需要更新的条件
 * @param array $needUpdateFields  需要更新的字段
 * @return bool|int  更新数据的条数
 */
function db_batch_update(string $tableName, array $where, array $needUpdateFields)
{

    if (empty($where) || empty($needUpdateFields)) return false;
    // 第一个条件数组的值
    $firstWhere = $where[array_key_first($where)];
    // 第一个条件数组的值的总数量
    $whereFirstValCount = count($firstWhere);
    // 需要更新的第一个字段的值的总数量
    $needUpdateFieldsValCount = count($needUpdateFields[array_key_first($needUpdateFields)]);
    if ($whereFirstValCount !== $needUpdateFieldsValCount) return false;
    // 所有的条件字段数组
    $whereKeys = array_keys($where);

    // 绑定参数
    $building = [];

//        $whereArr = [
//          0 => "id = 180 AND ",
//          1 => "user_id = 5 AND ",
//          2 => "id = 181 AND ",
//          3 => "user_id = 15 AND ",
//          4 => "id = 182 AND ",
//          5 => "user_id = 11 AND ",
//          6 => "id = 183 AND ",
//          7 => "user_id = 1 AND ",
//        ]
    $whereArr = [];
    $whereBuilding = [];
    foreach ($firstWhere as $k => $v) {
        foreach ($whereKeys as $whereKey) {
//                $whereArr[] = "{$whereKey} = {$where[$whereKey][$k]} AND ";
            $whereArr[] = "{$whereKey} = ? AND ";
            $whereBuilding[] = $where[$whereKey][$k];
        }
    }

//        $whereArray = [
//            0 => "id = 180 AND user_id = 5",
//            1 => "id = 181 AND user_id = 15",
//            2 => "id = 182 AND user_id = 11",
//            3 => "id = 183 AND user_id = 1",
//        ]
    $whereArrChunck = array_chunk($whereArr, count($whereKeys));
    $whereBuildingChunck = array_chunk($whereBuilding, count($whereKeys));

    $whereArray = [];
    foreach ($whereArrChunck as $val) {
        $valStr = '';
        foreach ($val as $vv) {
            $valStr .= $vv;
        }
        // 去除掉后面的 AND 字符及空格
        $whereArray[] = rtrim($valStr, "AND ");
    }

    // 需要更新的字段数组
    $needUpdateFieldsKeys = array_keys($needUpdateFields);

    // 拼接 sql 语句
    $sqlStr = '';
    foreach ($needUpdateFieldsKeys as $needUpdateFieldsKey) {
        $str = '';
        foreach ($whereArray as $kk => $vv) {
//                $str .= ' WHEN ' . $vv . ' THEN ' . $needUpdateFields[$needUpdateFieldsKey][$kk];
            $str .= ' WHEN ' . $vv . ' THEN ? ';
            // 合并需要绑定的参数
            $building[] = array_merge($whereBuildingChunck[$kk], [$needUpdateFields[$needUpdateFieldsKey][$kk]]);
        }
        $sqlStr .= $needUpdateFieldsKey . ' = CASE ' . $str . ' ELSE ' . $needUpdateFieldsKey . ' END, ';
    }

    // 去除掉后面的逗号及空格
    $sqlStr = rtrim($sqlStr, ', ');

    $tblSql = 'UPDATE ' . $tableName . ' SET ';

    $tblSql = $tblSql . $sqlStr;

    $building = array_reduce($building,"array_merge",array());
//        return [$tblSql, $building];
    return DB::update($tblSql, $building);
}

