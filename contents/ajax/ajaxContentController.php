<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File    : ajaxContentController.php
 * Author  : Anton Tarkhanov
 * Email   : imkekx@gmail.com
 * Type    : controller
 * 
 * Description:
 */
class ajaxContentController extends absContentController{

    public function build(){

        header('Content-type: text/json');
        $err_data = array('err'=>'true', 'msg'=>'Не верный формат запроса');
        $this->setView('default');

        $req = SS::getRequest();
        //Если параметров запроса нет, значит что-то не так
        if($req->params_count() != 1){
            $this->setData($err_data);
            return;
        }

        $target = $req->param(0);

        /* @var $ajax absAjax */
        $ajax   = $this->loadAjaxClass($target);

        if(!$ajax){
            $this->setData($err_data);
            return;
        }

        $act = $req->post('act','cmd');
        if(!$act){
            $this->setData($err_data);
            return;
        }

        $user = SS::getUser();
        if(!$user->perms->contentPerm($target,'ajax_'.$act)){
            $perms = array('err'=>'true', 'msg'=>'Недостаточно прав для выполнения операции');
            $this->setData($perms);
            return;
        }

        if(!method_exists($ajax,$act)){
            $this->setData($err_data);
            return;
        }

        $result = $ajax->$act();
        if($result === null){
            $this->setData($err_data);
            return;
        }else {

            $vw = $ajax->view();
            if($vw !== null) $vw = ROOT_PATH."/contents/".$ajax->objectName()."/ajax/views/".$vw.'.php';

            $data = array('view' => $vw, 'data' => $result);
            $this->setData($data);
        }

    }

    private function loadAjaxClass($name){


        $apath = ROOT_PATH."/contents/".$name."/ajax/".$name.'Ajax.php';
        if(file_exists($apath)){

            require_once($apath);
            $class = $name.'Ajax';
            return new $class();
        }else return null;
    }
}