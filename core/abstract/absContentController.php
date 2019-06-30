<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : absContentController.php
 * Type  : abstarct
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * 
 * Description:
 * Абстрактный класс для реализации контроллера.
 */

abstract class absContentController implements IntRenderElement{

    /**
     * Конструктор.
     * Любой производный класс, переопределяющий конструктор, обязан выполнить
     * этот конструктор.
     */
    public function __construct(){
        $sub_name = get_class($this);
        defined('ADMIN_MODE') ? $find_str = 'AdminContentController' : $find_str = 'ContentController';
        $entry = strpos($sub_name,$find_str);
        $this->_ext_name = substr($sub_name,0,$entry);
    }

    /**
     * @see IntRenderElement
     * @return string
     */
    final public function type(){return 'dynamic';}

    /**
     * Устанавливает имя представления
     * @param string $view_name
     */
    public function setView($view_name){
        $this->_view = $view_name;
    }

    /**
     * Возвращает имя представления
     * @return string
     */
    public function View(){
        return $this->_view;
    }

    /**
     * Возвращает набор данных для вывода в представлении
     * @return mixed
     */
    public function &data(){
        return $this->_data;
    }

    /**
     * Возвращает имя контента
     * @return string
     */
    public function name(){
        return $this->_ext_name;
    }

    /**
     * Возвращает тип объекта
     * @return string
     */
    final public function obj_type(){
        return 'content';
    }

    /**
     * Устанавливает набор данных для вывода в представлении
     * @param mixed $d
     */
    protected function setData(&$d){
        $this->_data =& $d;
    }

    /**
     * Возвращает класс модели по ее имени
     *
     * @param string $name
     * @return mixed
     * @throws excFileExistsError
     */
    protected function getModel($name){

        defined('ADMIN_MODE') ? $a_pref = 'Admin' : $a_pref = '';

        $obj_name   = $this->_ext_name.ucfirst($name).$a_pref.'Model';
        $models_dir = CONTENTS_PATH.'/'.$this->_ext_name.'/models';
        $obj_file   = $models_dir.'/'.$obj_name.'.php';

        if(!file_exists($obj_file)){
            throw new excFileExistsError($obj_file);
        }

        require_once $obj_file;

        return new $obj_name();
    }

    /**
     * Возвращает хелпер класс по его имени
     *
     * @param string $name
     * @return mixed
     * @throws excFileExistsError
     */
    protected function getHelper($name){

        defined('ADMIN_MODE') ? $a_pref = 'Admin' : $a_pref = '';

        $obj_name   = $this->_ext_name.ucfirst($name).$a_pref.'Helper';
        $models_dir = CONTENTS_PATH.'/'.$this->_ext_name.'/helpers';
        $obj_file   = $models_dir.'/'.$obj_name.'.php';

        if(!file_exists($obj_file)){
            throw new excFileExistsError($obj_file);
        }

        require_once $obj_file;

        return new $obj_name();
    }

    /**
     * В это свойство записывается название контента реализующего контроллер
     * Напр.: Если контент реализующий данный класс называется user , то его
     * контроллер будет называться userContentController , а в свойство _ext_name
     * будет записано только имя 'user'.
     * Используется для построения путей до папки контента, т.к. название контента
     * совпадает с названием папки, где он расположен.
     *
     * @var string
     */
    private $_ext_name;

    /**
     * Имя представления
     * @var string
     */
    private $_view;

    /**
     * Набор данных для вывода в представлении
     * @var mixed
     */
    private $_data;
}