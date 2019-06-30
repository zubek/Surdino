<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : excDbError.php
 * Type  : exception
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * Description:
 */
class excDbError extends Exception{

    /**
     * @param array $errors
     */
    function __construct($errors){

        $err_str = '';
        foreach($errors as $value){

            $err_str .= '[ERR: '.$value['errno'].'] '.$value['error']."\n";
        };

        parent::__construct($err_str);
    }
}