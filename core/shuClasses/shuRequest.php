<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : shuRequest.php
 * Type  : core
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * Description:
 * Объект содержит информацию по текущему запросу.
 * Все запросы в системе Shu имеют вид domain_name/controller/param1/param2/.../paramN/
 * Первый параметр является именем контроллера, если параметров нет, то используется
 * контроллер заданный по умолчанию.
 *
 * В строке запроса так же могут содержаться GET переменные. За их обработку полностью
 * отвечает контроллер.
 */
class shuRequest{

    function __construct(){

        $this->_controller_name    = null;

        $this->parse_request();
    }

    public function urlPath(){
        return $this->_urlpath;
    }

    /**
     * Возвращает значение параметра по его прядковому номеру
     *
     * @param $i int
     * @return string|null
     */
    public function param($i){

        if(isset($this->_params[$i])) return $this->_params[$i];
        return null;
    }

    /**
     * Возвращает количество параметров в запросе
     *
     * @return int
     */
    public function params_count(){
        return $this->_pars_count;
    }

    /**
     * Возвращает имя текущего контроллера
     *
     * @return string
     */
    public function controller(){
        return $this->_controller_name;
    }

    /**
     * Возвращает POST переменную, если она определена и соответствует заданному шаблону
     *
     * @param  $name          string             имя POST переменной
     * @param  $pattern       string          название паттерна
     * @param  mixed $default
     * @return null
     */
    public function post($name, $pattern, $default = null){

        if(!isset($_POST[$name])) return $default;
        if(shuValid::verify($_POST[$name], $pattern) === false) return null;
        return $_POST[$name];
    }

    public function get($name){
        if(!isset($this->_getvars[$name])) return null;
        else return $this->_getvars[$name];
    }

    public function get_str(){
        return $this->_getstr;
    }

    /**
     * Разбирает запрос и проверяет его коректность
     * В этой функции происходи определение контроллера ответственного за
     * вывод содержимого документа
     *
     * @throws excRequestError
     */
    private function parse_request(){

        $this->_params     = array();
        $this->_pars_count = 0;

        $set  = SS::getSettings();
        $ruri = $_SERVER['REQUEST_URI'];

        $rewrite = $this->check_route($ruri);
        if($rewrite){
            shuApp::move_permanent($rewrite);
        };

        define('ADMIN_TAG', $set->admin_tag);

        #Если в запросе переданы GET параметры, то отделим их от запроса.
        #В результате $_request содержит адрес запроса, а $_getstr содержит строку параметров
        if(strpos($ruri,'?') !== false){
            $parts          = explode('?',$ruri);
            $this->_urlpath = $parts[0];
            $this->_getstr  = $parts[1];
        }else{
            $this->_urlpath = $ruri;
            $this->_getstr  = null;
        };

        if($this->_getstr !== null) parse_str($this->_getstr,$this->_getvars);

        $opt = SS::getSiteOptions();
        #Если обращение к корню сайта, то предложим контроллер по умолчанию
        if($this->_urlpath == '/'){

            $this->_controller_name = $opt->sysOpt('default_controller');
            define('FRONTPAGE', 1);
            return;
        };

        if(!shuValid::UrlPath($this->_urlpath)){

            throw new excRequestError();
        };

        #Разберем путь запроса в массив
        $args = explode("/",$this->_urlpath);
        #Ключевым является первый элемент, он указывает на контроллер
        #Исключением является ситуация когда первый параметр равен тегу админской части
        $farg = strtolower($args[1]);

        $p_start = 2;

        #Если первый параметр равен тегу админки, то начинаем разбирать адрес со второго параметра
        #который теперь является названием контроллера
        if($set->admin_tag == $farg){

            define('ADMIN_MODE', 1); #теперь мы в зоне админки
            if(!isset($args[2]) || empty($args[2])) $this->_controller_name = $opt->sysOpt('default_admin_controller');
            else {
                $this->_controller_name = strtolower($args[2]);
                $p_start = 3;
            };

        }else  $this->_controller_name = $farg;

        #Парсим оставшиесся параметры в массив
        $this->_pars_count = 0;
        foreach($args as $a => $b) {

            if($a < $p_start) continue;
            if(empty($b)) continue;
            $this->_params[] = strtolower($b);
            $this->_pars_count++;
        };

        if($this->_pars_count == 0 && $this->_controller_name == $opt->sysOpt('default_controller'))  define('FRONTPAGE', 1);
    }

    /**
     * Проверяет, нужно ли переписывать адрес запроса в соответствии с настройками
     *
     * @param $url string
     * @return string
     */
    private function check_route($url){

        $db = SS::getDB();
        $search = $db->real_escape_string($url);
        $db->query("SELECT `rewrite` FROM `routing` WHERE `origin` = '$search'");
        if($db->countRows() != 1) return null;

        $row = $db->loadObject();
        return $row->rewrite;
    }

    /**
     * Путь URL документа
     * @var string
     */
    private $_urlpath;

    /**
     * Строка GET перемнных, если имеется
     * @var string
     */
    private $_getstr;

    /**
     * Имя контроллера, ответственного за вывод контента
     * @var string
     */

    /**
     * Массив GET параметров, либо null , если не определены
     * @var array|null
     */
    private $_getvars;

    private $_controller_name;

    /**
     * Массив параметров переданных в запросе
     * @var array
     */
    private $_params;

    /**
     * Количество параметров переданных в запросе
     * @var int
     */
    private $_pars_count;
}