<?php

/**
 * Created by PhpStorm.
 * User: Ciprian
 * Date: 08/04/2015
 *
 *
 * By calling this static class will return the values from global configurations inside of init.php file,
 */
class Config{
    public static function get($path = null){
        if($path){
            $config = $GLOBALS['config'];
            $path = explode('/', $path);
            foreach($path as $bit){
                if(isset($config[$bit])){
                    $config = $config[$bit];
                }
            }
            return $config;
        }
        return false;
    }
}
