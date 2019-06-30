<?php defined('SHU_CORED') or die('Access denied.');
/**
 * Created by PhpStorm.
 * User: tyva
 */


class catalogContentController extends absAdvContentController{

    public function default_handler(){

        $this->setView('default');

        $data = array();
        $data["privet"] = "hallo";

        $this->setData($data);

    }

}