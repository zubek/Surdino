<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : boot.php
 * Type  : core
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * Description:
 */

require ROOT_PATH.'/core/defines.php';

/**
 * Автозагрузчик классов. Автоматически включает необходимые файлы классов ядра.
 * Классы ядра начинаются с префиксов shu , abs , exc , Int:
 *
 * shu - Классы ядра
 * abs - Абстрактные классы ядра
 * exc - Классы исключений ядра
 * Int - Интерфейсы ядра
 *
 * @param string $class_name
 */
function core_autoloader($class_name){
    if($class_name == 'SS' || substr($class_name,0,3) == 'shu') {include(CORE_PATH.'/shuClasses/'.$class_name.'.php'); return;};
    if(substr($class_name,0,3) == 'abs') {include(CORE_PATH.'/abstract/'.$class_name.'.php'); return;};
    if(substr($class_name,0,3) == 'exc') {include(CORE_PATH.'/exceptions/'.$class_name.'.php'); return;};
    if(substr($class_name,0,3) == 'Int'){include(CORE_PATH.'/interfaces/'.$class_name.'.php'); return;};
}

spl_autoload_register('core_autoloader');

/**
 * Обработчик исключений
 *
 * @param Exception $exception
 */
function exception_handler($exception) {

    if(error_reporting() == 0){

        //TODO: Запись в лог файл
        return;
    }

    #При выбросе исключения очистим весь буфер вывода и выведем только лог ошибки
    while (ob_get_level()) {
        ob_end_clean();
    };

    $set = SS::getSettings();
    if($set->debug_mode) die('Произошел выброс исключения: '.$exception->getMessage());
    else die('Внутренняя ошибка сервера, попробуйте вернуться на главную страницу.');
}

set_exception_handler('exception_handler');

function mb_ucfirst($str, $encoding = 'UTF-8') {
    return mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding) . mb_strtolower(mb_substr($str, 1, mb_strlen($str), $encoding), $encoding);
}