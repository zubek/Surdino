<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File    : absAdvContentController.php
 * Author  : Anton Tarkhanov
 * Email   : imkekx@gmail.com
 * Type    : abstract
 * 
 * Description:
 */
abstract class absAdvContentController extends absContentController{

    public function __construct(){
        parent::__construct();
    }

    public function build(){

        $req = SS::getRequest();

        $action = $req->post('action','cmd');

        ($req->params_count() == 0) ? $front = true : $front = false;

        if($front) $method_name = 'default_handler';
        else $method_name = 'page_'.str_replace('-','_', $req->param(0));

        if($action)  $method_name .= '_action_'.str_replace('-','_',strtolower($action));

        if(method_exists($this,$method_name)) $this->$method_name();
        else{
            throw new excRequestError();
        }
    }

    abstract function default_handler();
}