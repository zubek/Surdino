<?php defined('SHU_CORED') or die('Access denied.');
/**
 * Created by PhpStorm.
 * User: tyva
 */

class registerContentController extends absAdvContentController{

    public function default_handler(){

        $this->setView('default');

    }

}