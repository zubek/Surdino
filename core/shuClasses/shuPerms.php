<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : shuPerms.php
 * Type  : core
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * 
 * Description:
 * Класс содержит привилегии группы пользователей
 * Данный класс используется в качестве свойства класса shuUser
 * @see shuUser
 */

class shuPerms{

    /**
     * Конструктор. По умолчанию загружает привилегии анонимного пользователя
     *
     * @param int $rid
     */
    function __construct($rid = 1){

        $this->load_role_permissions($rid);
    }

    /**
     * Проверяет наличие права заданного в pname у объекта контента с именем obj
     * В случае если право установлено возвращается true, иначе false
     *
     * @param string $obj
     * @param string $pname
     * @return bool
     */
    public function contentPerm($obj,$pname){
        if(isset($this->_perms['content'][$obj][$pname])) return $this->_perms['content'][$obj][$pname];
        else return false;
    }

    /**
     * Проверяет наличие права заданного в pname у объекта блока с именем obj
     * В случае если право установлено возвращается true, иначе false
     *
     * @param string $obj
     * @param string $pname
     * @return bool
     */
    public function blockPerm($obj,$pname){
        if(isset($this->_perms['block'][$obj][$pname])) return $this->_perms['block'][$obj][$pname];
        else return false;
    }

    /**
     * Проверяет наличие права заданного в pname у системного объекта с именем obj
     * В случае если право установлено возвращается true, иначе false
     *
     * @param string $obj
     * @param string $pname
     * @return bool
     */
    public function sysPerm($obj,$pname){
        if(isset($this->_perms['system'][$obj][$pname])) return $this->_perms['system'][$obj][$pname];
        else return false;
    }

    /**
     * Загружает права пользовательской группы
     *
     * @param int $rid
     * @throws excDbError
     */
    private function load_role_permissions($rid){

        $db    = SS::getDB();
        $set   = SS::getSettings();

        if($set->auth_strict === true){
            $query = "SELECT `name`,`type`,`object` FROM `shu_role_perms` WHERE `rid` = 1";
            if($rid != 1) $query .= " OR `rid` = 2 OR `rid` = $rid";
        }else{

            $query = "SELECT `name`,`type`,`object` FROM `shu_role_perms` WHERE `rid` = 1";
            if($rid != 1) $query .= " OR `rid` = $rid";
        };

        if(!$db->query($query)){
            throw new excDbError($db->error_list);
        };

        $this->_perms = array();
        while($row = $db->loadRow()){
            $this->_perms[$row['type']][$row['object']][$row['name']] = true;
        }
    }

    private $_perms;
}