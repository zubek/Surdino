<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File    : empty.php
 * Author  : Anton Tarkhanov
 * Email   : imkekx@gmail.com
 * Type    : view
 * 
 * Description:
 *
 * @var $data array
 */

if($data['view'] !== null) require $data['view'];
else{

    $result = new stdClass();
    $pref = 'data';
    $result->$pref = $data['data'];
    echo json_encode($result);
}