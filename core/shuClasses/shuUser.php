<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : shuUser.php
 * Type  : core
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * 
 * Description:
 * Класс содержит информацию о пользователе.
 *
 * Авторизацию пользователя следует проверять с помощью функции isAuth()
 * Если isAuth() возвращает false значит юзер не атворизован, а в экземпляре
 * загружены данные для анонимного пользователя.
 */
class shuUser{

    /**
     * Идентификатор пользователя
     * @var int
     */

    public $uid;
    /**
     * Идентификатор группы пользователя
     * @var int
     */
    public $rid;

    /**
     * Логин пользователя
     * @var string
     */
    public $login;

    /**
     * Имя пользователя
     * @var string
     */
    public $name;

    /**
     * Почта пользователя
     * @var string
     */
    public $email;

    /**
     * Статус пользователя, может принимать одно из трех значений:
     *
     * 'active'      - пользователь активен
     * 'blocked'     - пользователь заблокирован
     * 'bobtail'     - пользователь активен, однако его права урезаны до минимума
     *
     * @var string
     */
    public $status;

    /**
     * Дата и время регистрации пользователя в системе
     * @var datetime
     */
    public $datereg;

    /**
     * Дата и время последнего визита пользователя
     * @var datetime
     */
    public $lastvisit;

    /**
     * Объект содержит привилегии пользовательской группы
     * @var shuPerms
     */
    public $perms;

    /**
     * Конструктор. Загружает пользователя с заданным логином и паролем.
     * Либо в случае их отсутствия, создает непривилигоравнного пользователя.
     *
     * @param int $uid
     */
    function __construct($uid = 0){

        if($uid == 0){
            $this->load_anon();
        }else{
            $this->loadFromId($uid);
        };

        $this->perms = new shuPerms($this->rid);
        $this->init_city();
    }

    /**
     * Загружает анонимного пользователя
     */
    private function load_anon(){
        $this->_isauth = false;
        $this->_params = array();
        $this->uid     = 0;
        $this->rid     = 1;
        $this->_errMsg = '';
    }

    /**
     * Загружает данные пользователя по логину и паролю, возвращает true в случае успеха,
     * иначе false. Если возвращаемое значение равно false , то в свойстве $_errMsg записывается
     * текст ошибки.
     *
     * @param string $l логин
     * @param string $p пароль
     * @return bool
     * @throws excDbError
     */
    public function load($l, $p){

        if(!shuValid::Login($l)){
            $this->load_anon();
            $this->_errMsg = 'Использованы недопустимые символы в логине.';
            return false;
        };

        if(!shuValid::Password($p)){
            $this->load_anon();
            $this->_errMsg = 'Использованы недопустимые символы в пароле.';
            return false;
        };

        $db    = SS::getDB();
        $md5pass = md5($p);
        $query = "SELECT `uid`,`rid`,`login`,`email`,`status`,`name`,`date_reg`,`last_visit`,`params` FROM `shu_users` WHERE `login` = '$l' AND `password` = '$md5pass'";
        if(!$db->query($query)){
            throw new excDbError($db->error_list);
        };

        if($db->countRows() == 0){
            $this->load_anon();
            $this->_errMsg = 'Пользователь с таким логином и паролем не найден.';
            return false;
        };

        $obj = $db->loadObject();

        $this->load_from_object($obj);

        $this->_isauth = true;
        return true;
    }

    /**
     * Загружает данные пользователя по идентификатору, возвращает true в случае успеха,
     * иначе false. Если возвращаемое значение равно false , то в свойстве $_errMsg записывается
     * текст ошибки.
     *
     * @param int $uid идентификатор пользователя
     * @return bool
     * @throws excDbError
     */
    public function loadFromId($uid){
        $db    = SS::getDB();
        $query = "SELECT `uid`,`rid`,`login`,`email`,`status`,`name`,`date_reg`,`last_visit`,`params` FROM `shu_users` WHERE `uid` = $uid ";
        if(!$db->query($query)){
            throw new excDbError($db->error_list);
        };

        if($db->countRows() == 0){
            $this->load_anon();
            $this->_errMsg = 'Пользователь с идентификатором: '.$uid.' не найден';
            return false;
        };

        $obj = $db->loadObject();

        $this->load_from_object($obj);
        $this->_isauth = true;
        return true;
    }

    /**
     * Возвращает текст ошибки авторизации
     * @return string
     */
    public function errMsg(){
        return $this->_errMsg;
    }

    /**
     * Возвращает true если пользователь авторизован, иначе false
     * @return bool
     */
    public function isAuth(){
        return $this->_isauth;
    }

    public function setCity($sys_name){
        $db = SS::getDB();
        $db->query("SELECT `id`,`name`,`city_tel`,`sys_name` FROM `cities` WHERE `sys_name` = '$sys_name'");

        $sess = SS::getSession();
        $so = $sess->getVar('session_object');
        $ct = $db->loadObject();
        $so->user_city = array('id' => $ct->id, 'name' => $ct->name, 'sys_name' => $ct->sys_name, 'city_tel' => $ct->city_tel, 'version_2' => true);
        setcookie('at_user_city_id',$so->user_city['id']);
    }

    /**
     * Загружает данные о пользователе из объекта.
     * Объект получается в результате выполнения запроса к базе данных.
     * @see load()
     * @see loadFromId()
     *
     * @param stdClass $obj
     */
    private function load_from_object($obj){

        $this->uid       = $obj->uid;
        $this->rid       = $obj->rid;
        $this->login     = $obj->login;
        $this->email     = $obj->email;
        $this->status    = $obj->status;
        $this->name      = $obj->name;
        $this->datereg   = $obj->date_reg;
        $this->lastvisit = $obj->last_visit;
        $this->_params   = json_decode($obj->params,true);

        if($this->_params === null) $this->_params = array();
    }

    private function init_city(){

        $sess = SS::getSession();
        $so = $sess->getVar('session_object');
        if($sess->getVar('refreshed_city') === null){
            $sess->setVar('refreshed_city',true);
            $so->user_city = array();
        }

        if(isset($so->user_city['id']) && !isset($so->user_city['version_2'])) unset($so->user_city);

        if(!isset($so->user_city['id'])){

            /** @var libIpgeobase $geo */
            $geo = SS::getLib('ipgeobase');
            $data = $geo->getRecord($_SERVER['REMOTE_ADDR']);

            switch($data['city']){

                case 'Санкт-Петербург':
                    $c_city = 'st-petersburg';
                    break;
                case 'Рязань':
                    $c_city = 'ryazan';
                    break;
                case 'Новосибирск':
                    $c_city = 'novosibirsk';
                    break;
                case 'Красноярск':
                    $c_city = 'krasnoyarsk';
                    break;
                case 'Иркутск':
                    $c_city = 'irkutsk';
                    break;
                case 'Улан-Удэ':
                    $c_city = 'ulan-ude';
                    break;
                case 'Владивосток':
                    $c_city = 'vladivostok';
                    break;
                case 'Хабаровск':
                    $c_city = 'khabarovsk';
                    break;
                case 'Артем':
                    $c_city = 'artem';
                    break;

                default:
                    $c_city = 'moscow';
                    break;
            }


            $db = SS::getDB();
            $db->query("SELECT `id`,`name`,`city_tel`,`sys_name` FROM `cities` WHERE `sys_name` = '$c_city'");

            $ct = $db->loadObject();
            $so->user_city = array('id' => $ct->id, 'name' => $ct->name, 'sys_name' => $ct->sys_name, 'city_tel' => $ct->city_tel, 'version_2' => true);
        }

        setcookie('at_user_city_id',$so->user_city['id']);
    }

    private $_errMsg;
    private $_params;
    private $_isauth;

}