<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : shuValid.php
 * Type  : core
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * 
 * Description:
 * Класс имеющий только статичные методы предназначен для валидации различных значений
 */
define('SHUVALID_URLPATH',"/^\\/([a-zA-Z0-9_\\-:\\.]+\\/?)+$/");
define('SHUVALID_LOGIN',"/^[a-zA-Z0-9_\\-\\.@]+$/");
define('SHUVALID_PASSWORD',"/^[a-zA-Z0-9_\\-:\\.@#$;]+$/");
define('SHUVALID_CMD',"/^[A-Z0-9_-]+$/i");
define('SHUVALID_ENWORD',"/^[A-Z]+$/i");
define('SHUVALID_MB_WORDS',"/^[A-ZА-Я\\s\\-_\\.0-9]+$/ui");
define('SHUVALID_INT',"/^[0-9]+$/");
define('SHUVALID_DATE',"/^\\d\\d\\.\\d\\d\\.\\d\\d\\d\\d$/");


class shuValid{

    /**
     * Проверяет корректность URL адреса.
     * Стоит обратить внимание, что данная проверка относится исключительно
     * к пути документа без его параметров (после знака '?')
     * Например:
     *      /user/login/      - правильный адрес
     *      /user/?opt=login  - неправильный адрес
     *
     * @param string $str
     * @return bool
     */
    public static function urlpath($str){
        return (preg_match(SHUVALID_URLPATH, $str) == 0) ? false:true;
    }

    /**
     * Проверяет строку на корректность по шаблону логина
     * @param string $str
     * @return bool
     */
    public static function login($str){
        return (preg_match(SHUVALID_LOGIN, $str) == 0) ? false:true;
    }

    /**
     * Проверяет строку на корректность по шаблону пароля
     * @param string $str
     * @return bool
     */
    public static function password($str){
        return (preg_match(SHUVALID_PASSWORD, $str) == 0) ? false:true;
    }

    /**
     * Проверяет строку на корреткность по шаблону команды
     * Команда это строка, которая может содержать только буквы
     * латинского алфавита, знак подчеркивания и тире не зависимо от регистра.
     *
     * @param string $str
     * @return bool
     */
    public static function cmd($str){
        return (preg_match(SHUVALID_CMD, $str) == 0) ? false:true;
    }

    /**
     * Проверяет строку на корректность по шаблону английского слова.
     * Английское слово может содержать только буквы латинского алфавита без пробелов и других
     * символов.
     *
     * @param string $str
     * @return bool
     */
    public static function enword($str){
        return (preg_match(SHUVALID_ENWORD, $str) == 0) ? false:true;
    }

    /**
     * Проверяет строку на корректность по шаблону урл адреса
     *
     * @param $str string
     * @return bool
     */
    public static function full_url($str){
        return (preg_match(SHUVALID_FULL_URL, $str) == 0) ? false:true;
    }

    /**
     * Проверяет строу на корректность по шаблону предложения.
     * Предложение может содержать буквы русского и латинского алфавита,
     * пробел тире и исмвол подчеркивания а так же цифры.
     *
     * @param string $str
     * @return bool
     */
    public static function mb_words($str){
        return (preg_match(SHUVALID_MB_WORDS, $str) == 0) ? false:true;
    }

    public static function raw($str = null){
        return true;
    }

    public static function num($str){
        return (preg_match(SHUVALID_INT, $str) == 0) ? false:true;
    }

    public static function date($str){
        return (preg_match(SHUVALID_DATE, $str) == 0) ? false:true;
    }

    public static function strip_php($source){

        return preg_replace("/(\\r?\\n*)*<\\?.*\\?>(\\r?\\n*)*/","",$source);
    }

    /**
     * Метод проверяет строку $val на соответствие заданному шаблону
     *
     * @param string $val        значение требующее валидации
     * @param string $pattern    имя шаблона для проверки
     * @return bool
     * @throws excSystemError
     */
    public static function verify($val, $pattern){

        if(method_exists('shuValid', $pattern)){
            return self::$pattern($val);
        }else throw new excSystemError('Метод '.$pattern.' не определен в классе shuValid');
    }
}