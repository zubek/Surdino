<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : shuSession.php
 * Type  : core
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * Description:
 * Класс реализующий доступ к переменным сессии.
 */
class shuSession{

    /**
     * Конструктор
     */
    function __construct(){
        session_name(SHU_SESSIONNAME);
        session_start();
        $this->_sid = session_id();
        if(!isset($_SESSION['session_object']) || !is_object($_SESSION['session_object'])) $_SESSION['session_object'] = new shuObject();
    }

    /**
     * Возвращает значение переменной сессии по ее имени либо значение по умолчанию
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getVar($name, $default = null){

        if(isset($_SESSION[$name])) return $_SESSION[$name];
        else return $default;
    }

    /**
     * Устанавливает переменную в сессию.
     * Возвращает true , если переменная была установлена.
     * Функция может вернуть false , только если в параметре $overwrite передано значение false
     * и переменная уже существует в окружении сесси.
     *
     * @param  string $name
     * @param  mixed  $val
     * @param  bool   $overwrite
     * @return bool
     */
    public function setVar($name, $val, $overwrite = true){

        if(isset($_SESSION[$name]) && $overwrite == false) return false;
        else {
            $_SESSION[$name] = $val;
            return true;
        }
    }

    /**
     * Уничтожает переменную из окружения сессии
     *
     * @param string $name
     */
    public function unsetVar($name){

        if(isset($_SESSION[$name])) unset ($_SESSION[$name]);
    }

    /**
     * Уничтожает текущую сессию
     */
    public function destroy(){

        $_SESSION = array();
        unset($_COOKIE[session_name()]);
        session_destroy();
    }

    /**
     * Устанавливает время жизни сессии начиная от текущего времени
     * продолжительностью $val секунд.
     *
     * @param int $val
     */
    public function setSessionTime($val){

        setcookie(SHU_SESSIONNAME, $this->_sid,time() + $val,'/',null,0,true);
    }

    /**
     * Содержит текущий идентификатор сессии
     * @var string
     */
    private $_sid;
}