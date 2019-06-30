<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : excFileExistsError.php
 * Type  : exception
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * Description:
 */
class excFileExistsError extends Exception{

    function __construct($file){

        parent::__construct('Обращение к несуществующему файлу: '.$file);
    }
}