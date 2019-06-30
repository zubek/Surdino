<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : shuNotes.php
 * Type  : core
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * 
 * Description:
 */
class shuNotes{

    function __construct(){

        $sess = SS::getSession();
        $this->_notes = $sess->getVar('shu_notes');
        if($this->_notes === null) $this->_notes = array();
    }

    function __destruct(){

        unset($_SESSION['shu_notes']);
        $_SESSION['shu_notes'] = $this->_notes;
    }

    public function setNote($object, $name, $value){

        $this->_notes[$object][$name] = $value;
    }

    public function getNotes($object){

        if(isset($this->_notes[$object])){

            $result = $this->_notes[$object];
            unset($this->_notes[$object]);
            return $result;
        }else return null;
    }

    public function have($object){
        if(isset($this->_notes[$object])) return true;
        else return false;
    }

    private $_notes;
}