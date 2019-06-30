<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : SS.php
 * Type  : core
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * 
 * Description:
 *
 * Класс возвращает объекты ядра в виде синглтонов
 */

class SS{

    /**
     * Возвращает экземпляр приложения
     * @return shuApp
     */
    public static function getApp(){

        static $_app = null;
        if(!$_app) $_app = new shuApp();
        return $_app;
    }

    /**
     * Возвращает класс настроек приложения
     * @see root_path/settings.php
     * @return appSettings
     */
    public static function getSettings(){

        static $_settings = null;
        if(!$_settings) $_settings = new appSettings();
        return $_settings;
    }

    /**
     * Возвращает экземпляр класса для работы с базой данных MySQL
     * @see shuDB
     * @return shuDB
     */
    public static function getDB(){

        static $_db = null;
        if(!$_db) {

            $set = self::getSettings();
            $p = array();
            $p['host']       = $set->db_host;
            $p['user']       = $set->db_user;
            $p['password']   = $set->db_password;
            $p['name']       = $set->db_name;
            $p['use_timers'] = $set->use_timers;

            $_db = new shuDB($p);
        };

        return $_db;
    }

    /**
     * Возвращает объект shuRequest для работы с запросом пользователя
     * @see shuRequest
     * @return shuRequest
     */
    public static function getRequest(){

        static $_req = null;
        if(!$_req){
            $_req = new shuRequest();
        };
        return $_req;
    }

    /**
     * Возвращает экземпляр контроллера по его имени
     *
     * @param string $ctrl_name            имя контроллера контента
     * @return absContentController
     * @throws excFileExistsError
     */
    public static function getContentController($ctrl_name){

        static $_cc = null;
        if(!$_cc){
            defined('ADMIN_MODE') ? $a_pref = 'Admin' : $a_pref = '';

            $ctrl_dir = CONTENTS_PATH.'/'.$ctrl_name;

            $obj_name = strtolower($ctrl_name);
            $obj_name .= $a_pref.'ContentController';

            $ctrl_file = $ctrl_dir.'/'.$obj_name.'.php';

            if(!file_exists($ctrl_file)){

                throw new excFileExistsError($ctrl_file);
            };

            require $ctrl_file;

            $_cc = new $obj_name();
        }

        return $_cc;
    }

    /**
     * Возвращает объект отвечающий за доступ к опциям сайта
     * @return shuSiteOptions
     */
    public static function getSiteOptions(){

        static $_opt = null;
        if(!$_opt)  $_opt = new shuSiteOptions();
        return $_opt;
    }

    /**
     * Возвращает экземпляр класса документа
     * @return shuDocument
     */
    public static function getDocument(){

        static $_doc = null;
        if(!$_doc) $_doc = new shuDocument();
        return $_doc;
    }

    /**
     * Возвращает экземпляр класса реализующего очередь рендеринга
     * @return shuRenderMap
     */
    public static function getRenderMap(){

        static $_rm = null;
        if(!$_rm) $_rm = new shuRenderMap();
        return $_rm;
    }

    /**
     * Возвращает объект реализующий визуализацию страницы
     * @return shuVisual
     */
    public static function getVisual(){

        static $_vis = null;
        if(!$_vis) $_vis = new shuVisual();
        return $_vis;
    }

    /**
     * Возвращает объект содержащий информацию о текущем пользователе.
     * Если пользователь не авторизован, возвращается объект анонимного
     * пользователя.
     *
     * @return shuUser
     */
    public static function getUser(){

        static $_user = null;
        if($_user) return $_user;

        $sess = self::getSession();
        if($sess->getVar('shu_auth_user') === true){

            $user_id = $sess->getVar('shu_user_id');
            $_user   = new shuUser($user_id);

        }else{

            $_user = new shuUser();
        }

        return $_user;
    }

    /**
     * Создает и возвращает контроллер блока по заданному имени.
     *
     *
     * @param string $name         - имя блока
     * @param mixed|null $params   - содержит параметры настроек блока
     * @return absBlockController
     */
    public static function createBlockController($name, $params = null){

        defined('ADMIN_MODE') ? $a_pref = 'Admin' : $a_pref = '';

        $obj_name  = strtolower($name).$a_pref.'BlockController';
        $block_dir = BLOCKS_PATH.'/'.$name;
        $c_file    = $block_dir.'/'.$obj_name.'.php';

        if(!file_exists($c_file)){
            return null;
        }

        require_once $c_file;

        $obj = new $obj_name($params);
        return $obj;
    }

    /**
     * Возвращает объект сессии
     * @return null|shuSession
     */
    public static function getSession(){

        static $_sess = null;
        if(!$_sess) $_sess = new shuSession();
        return $_sess;
    }

    /**
     * Возвращает объект заметок
     * @return shuNotes
     */
    public static function getNotes(){

        static $_notes = null;
        if(!$_notes) $_notes = new shuNotes();
        return $_notes;
    }

    /**
     * Возвращает класс библиотеки
     *
     * @param string $name
     * @return object
     * @throws excFileExistsError
     */
    public static function getLib($name){

        static $_libs = array();

        $lib_name = strtolower($name);
        if(isset($_libs[$lib_name]) && is_object($_libs[$lib_name])) return $_libs[$lib_name];

        $lib_dir = LIBS_PATH.'/'.$lib_name;
        $lib_file = $lib_dir.'/'.$lib_name.'.php';

        if(!file_exists($lib_file)){
            throw new excFileExistsError($lib_file);
        }

        require_once($lib_file);

        $class_name = 'lib'.ucfirst($lib_name);
        $_libs[$lib_name] = new $class_name();
        return $_libs[$lib_name];
    }
}