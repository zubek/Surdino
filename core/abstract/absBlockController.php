<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : absBlockController.php
 * Type  : abstract
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * 
 * Description:
 */
abstract class absBlockController implements IntRenderElement{

    /**
     * Конструктор.
     * Любой производный класс, переопределяющий конструктор, обязан выполнить
     * этот конструктор.
     *
     * @param mixed|null $params
     */
    public function __construct($params = null){

        $sub_name = get_class($this);
        defined('ADMIN_MODE') ? $find_str = 'AdminBlockController' : $find_str = 'BlockController';
        $entry = strpos($sub_name,$find_str);
        $this->_ext_name = substr($sub_name,0,$entry);
        $this->opts = $params;
    }

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
        return 'block';
    }

    /**
     * @see IntRenderElement
     * @return string
     */
    final public function type(){return 'dynamic';}

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

        $obj_name   = $this->_ext_name.ucfirst($name).$a_pref.'BlockModel';
        $models_dir = BLOCKS_PATH.'/'.$this->_ext_name.'/'.'models';
        $obj_file   = $models_dir.'/'.$obj_name.'.php';

        if(!file_exists($obj_file)){
            throw new excFileExistsError($obj_file);
        }

        require_once $obj_file;

        return new $obj_name();
    }

    /**
     * Хранит название контроллера блока
     * @var string
     */
    private $_ext_name;

    /**
     * Хранит название вида
     * @var string
     */
    private $_view;

    /**
     * Данные для отображения
     * @var mixed
     */
    private $_data;

    /**
     * Параметры блока
     * @var mixed
     */
    protected $opts;
}