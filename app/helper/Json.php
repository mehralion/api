<?php
/**
 * Created by PhpStorm.
 * User: nnikitchenko
 * Date: 17.12.2015
 */

namespace app\helper;


class Json
{
    public static function encode($data)
    {
        $data = self::array_encode($data);
        return json_encode($data);
    }

    public static function decode($data)
    {
        return json_decode($data);
    }

    protected static function array_encode(&$arr){
        array_walk_recursive($arr, function(&$val, &$key){
            /*
        	if(is_string($val))
                $val = iconv('windows-1251', 'utf-8', $val);
            if(is_string($key))
                $key = iconv('windows-1251', 'utf-8', $key);
*/
        });
        return $arr;
    }
}