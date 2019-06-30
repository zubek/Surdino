<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File    : absAjax.php
 * Author  : Anton Tarkhanov
 * Email   : imkekx@gmail.com
 * Type    : abstract
 * 
 * Description:
 */
abstract class absAjax{

    function __construct(){
        $sub_name = get_class($this);
        $find_str = 'Ajax';
        $entry = strpos($sub_name,$find_str);

        $this->_ext_name = substr($sub_name,0,$entry);
        $this->_ajax_view = null;
    }

    protected function getModel($name){
        defined('ADMIN_MODE') ? $a_pref = 'Admin' : $a_pref = '';

        $obj_name   = $this->_ext_name.ucfirst($name).$a_pref.'Model';
        $models_dir = CONTENTS_PATH.'/'.$this->_ext_name.'/models';
        $obj_file   = $models_dir.'/'.$obj_name.'.php';

        if(!file_exists($obj_file)){
            throw new excFileExistsError($obj_file);
        }

        require_once $obj_file;

        return new $obj_name();
    }

    final protected function setView($view_name){
        $this->_ajax_view = $view_name;
    }

    final public function view(){
        return $this->_ajax_view;
    }

    final public function objectName(){
        return $this->_ext_name;
    }

    private $_ajax_view;
    private $_ext_name;
}