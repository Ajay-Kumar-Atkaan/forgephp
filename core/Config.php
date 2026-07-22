<?php
declare(strict_types=1);
namespace Core;

class Config
{
    public static function get(string $configKey): array|string|null
    {
        $arr = explode('.',$configKey);
        $data = require_once basePath().'/config/'.$arr[0] . '.php';
        $len = count($arr);
        if($len == 2) {
            return $data[$arr[1]];
        }
        
        for($i = 1; $i < count($arr); $i++) {
            $data = $data[$arr[$i]];
        }
        return $data;
    }
}