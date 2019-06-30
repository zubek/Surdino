<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File    : shuObject.php
 * Author  : Anton Tarkhanov
 * Email   : imkekx@gmail.com
 * Type    : core
 * 
 * Description:
 */
class shuObject{

    function __set($name, $val){
        $this->$name = $val;
    }

    function __get($name){
        return null;
    }

}