<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : excRequestError.php
 * Type  : exception
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * Description:
 */
class excRequestError extends Exception{

    function __construct(){
        parent::__construct('[URL_ERROR]: "'.$_SERVER['REQUEST_URI'].'"');
    }
}