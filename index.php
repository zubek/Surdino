<?php define('SHU_CORED', 1);
/**
 * File    : index.php
 * Author  : Anton Tarkhanov
 * Email   : imkekx@gmail.com
 * Type    : entry
 * 
 * Description:
 */

define('ROOT_PATH', str_replace(DIRECTORY_SEPARATOR, '/' , getcwd()));

require 'settings.php';
require ROOT_PATH.'/core/boot.php';
//require 'parse.php';
SS::getApp()->run();