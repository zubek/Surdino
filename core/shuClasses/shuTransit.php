<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File    : shuTransit.php
 * Author  : Anton Tarkhanov
 * Email   : imkekx@gmail.com
 * Type    : core
 * 
 * Description:
 */
class shuTransit{

    static public function set($name, $data){

        if(!is_array(self::$_data)) self::$_data = array();
        self::$_data[$name] = $data;
    }

    static public function get($name, $default = null){
        if(!is_array(self::$_data)) return $default;
        if(!isset(self::$_data[$name])) return $default;
        return self::$_data[$name];
    }

    private static $_data;
}