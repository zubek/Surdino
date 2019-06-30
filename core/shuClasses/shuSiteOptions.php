<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : shuSiteOptions.php
 * Type  : core
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * Description:
 */
class shuSiteOptions{

    /**
     * Конструктор.
     * Загружает все значение опций сайта
     */
    function __construct(){

        $this->_sys_opts   = array();
        $this->_cont_opts  = array();
        $this->_block_opts = array();

        $db    = SS::getDB();
        $db->query('SELECT `name`,`value`,`type`,`obj_name` FROM `shu_options` WHERE 1 ');

        while($row = $db->loadRow()){

            if($row['type'] == 'system')  $this->_sys_opts[$row['name']] = $row['value'];
            if($row['type'] == 'content') $this->_cont_opts[$row['obj_name']][$row['name']] = $row['value'];
            if($row['type'] == 'block')   $this->_block_opts[$row['obj_name']][$row['name']] = $row['value'];

        };
    }

    /**
     * Возвращает значение системной опции
     *
     * @param string $name      имя опции
     * @return string|null
     */
    public function sysOpt($name){
        if(isset($this->_sys_opts[$name])) return $this->_sys_opts[$name];
        else return null;
    }

    /**
     * Возвращает значение опции контента
     *
     * @param $content  имя контента
     * @param $name     имя опции
     * @return string|null
     */
    public function contentOpt($content , $name){
        if(isset($this->_cont_opts[$content][$name])) return $this->_cont_opts[$content][$name];
        else return null;
    }

    /**
     * Возвращает значение опции блока
     *
     * @param $block  имя блока
     * @param $name   имя опции
     * @return string|null
     */
    public function blockOpt($block , $name){
        if(isset($this->_block_opts[$block][$name])) return $this->_block_opts[$block][$name];
        else return null;
    }

    /**
     * Хранит все значения системных опций в ассоциативном массиве
     * @var array
     */
    private $_sys_opts;

    /**
     * Хранит все значения опций для элементов контента в ассоциативном массиве
     * в качестве ключей выступает имя контента
     * @var array
     */
    private $_cont_opts;

    /**
     * Храниит все значения опций для блочных элементов в ассоциативном массиве
     * в качетсве ключей выступает имя блока
     * @var array
     */
    private $_block_opts;
}