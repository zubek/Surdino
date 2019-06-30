<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : defines.php
 * Type  : core
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 */

#Определим базовый URL с учетом протокола
$is_https      = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on';
$http_protocol = $is_https ? 'https' : 'http';
$base_url      = $http_protocol . '://' . $_SERVER['HTTP_HOST'];

define('SHU_SESSIONNAME','shuid');
define('SHU_COREVERSION', '1.0.2');

#Временные интервалы в секундах
define('SHU_MINUTE', 60);
define('SHU_HOUR'  , 3600);
define('SHU_DAY'   , 86400);
define('SHU_WEEK'  , 604800);
define('SHU_MONTH' , 2592000);    #30 дней
define('SHU_YEAR'  , 31536000);   #365 дней

#Определение URL путей
define('BASE_URL', $base_url);
define('MEDIA_URL', $base_url.'/media/');

#Определение путей файловой системы
define('CORE_PATH'      , ROOT_PATH.'/core');
define('TEMPLATES_PATH' , ROOT_PATH.'/tpls');
define('LIBS_PATH'      , ROOT_PATH.'/libs');
define('ADMIN_PATH'     , ROOT_PATH.'/admin');
define('ERROR_TPLS_PATH', ROOT_PATH.'/error_tpls');
define('MEDIA_PATH'     , ROOT_PATH.'/media');