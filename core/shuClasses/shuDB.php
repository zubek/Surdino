<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : shuDB.php
 * Type  : core
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * Description:
 * Класс для работы с базой данных MySQL
 */
class shuDB extends MySQLi{

    /**
     * Конструктор
     * @param array $array_config
     * @throws excDbError
     */
    function __construct($array_config)
    {
        $this->_query_count   = 0;
        $this->_error_query   = 0;
        $this->_success_query = 0;
        $this->_longest_query = '';
        $this->_longest_time  = 0.0;
        $this->_total_time    = 0.0;
        $this->_result        = false;
        $this->_query_info    = array();

        if(isset($array_config['use_timers'])) $this->_use_timers = $array_config['use_timers'];
        else $this->_use_timers = false;

        $this->_connected = false;
        parent::__construct($array_config['host'], $array_config['user'], $array_config['password'], $array_config['name']);
        if (!mysqli_connect_error())  {
            $this->_connected = true;
            $this->query("SET NAMES 'utf8'");

        }else throw new excDbError('Нет соединения с базой данных');
    }

    /**
     * Деструктор
     */

    function __destruct()
    {
        $this->CloseConnect();
    }

    /**
     * Функция закрывает соединение с базой данных и освобождает выделенные ресурсы
     */
    public function CloseConnect()
    {
        if(is_object($this->_result))
        {
            $this->_result->free_result();
        };

        if($this->_connected)
        {
            $this->close();
            $this->_connected = false;
        };
    }

    /**
     * Выполняет запрос к базе данных
     *
     * @param string $sql
     * @return bool
     */
    public function query($sql)
    {

        $this->_count_rows = 0;
        $start_t = 0.0;

        if($this->_use_timers)
        {
            $start_t = microtime(true);
        };

        $this->_result = parent::query($sql);

        if($this->_use_timers)
        {
            $end_t = microtime(true);
            $total_t = $end_t - $start_t;

            if($total_t > $this->_longest_time)   $this->_longest_time = $total_t;

            $this->_total_time += $total_t;
            $this->_total_time = round($this->_total_time,6);

            $inf = array('query' => $sql, 'time' => $total_t);
            $this->_query_info[] = $inf;
        };

        if($this->_result == false)
        {
            if($this->_use_timers) $this->_error_query++;
            return false;

        }else if($this->_use_timers) $this->_success_query++;

        if(is_object($this->_result)) $this->_count_rows = $this->_result->num_rows;
        return true;
    }

    public function multi_query($sql){
        if($this->_result = parent::multi_query($sql) === false) return false;

            do {
                if(!$this->next_result()) return false;
            } while ($this->more_results());

        return true;
    }


    /**
     * Извлекает следующую строку результата запроса в виде ассоциативного массива
     * @return mixed
     */
    public function loadRow()
    {
        if(is_object($this->_result))
            return $this->_result->fetch_assoc();
        else return null;
    }

    /**
     * Извлекает все строки результата запроса в виде массива ассоциативных массивов
     * @return array
     */
    public function loadRowList()
    {
        $a_ret = array();
        while($row = $this->loadRow())
            $a_ret[] = $row;

        return $a_ret;
    }

    /**
     * Извлекает следующую строку результата запроса в виде объекта
     * @return mixed
     */
    public function loadObject()
    {
        if(is_object($this->_result))
            return $this->_result->fetch_object();
        else return null;
    }

    /**
     * Извлекает все строки результата запроса в виде массива объектов
     * @return mixed
     */
    public function loadOjectList()
    {
        if(!is_object($this->_result)) return null;
        $a_ret = array();
        while($row = $this->loadObject())
            $a_ret[] = $row;
        return $a_ret;
    }

    /**
     * Возвращает количество строк в результирующем наборе
     * @return int
     */
    public function countRows(){
        return $this->_count_rows;
    }

    /**
     * Возвращает общее время затраченное на все запросы
     * @return float
     */
    public function execTime(){
        return $this->_total_time;
    }

    /**
     * Возвращает массив запросов и время их выполнения
     * @return array
     */
    public function queryTimers(){
        return $this->_query_info;
    }

    /**
     * Статус подключения к базе данных
     * @var bool
     */
    private     $_connected;

    /**
     * Содержит количество возвращенных строк
     * @var int
     */
    private     $_count_rows;

    /**
     * Содержит объект результирующего набора строк
     * @var mixed
     */
    private     $_result;

    /**
     * Счетчик количества запросов
     * @var int
     */
    private     $_query_count;

    /**
     * Общее время затраченное на выполнение запросов к базе данных
     * @var double
     */
    private     $_total_time;

    /**
     * Количество успешно выполненных запросов
     * @var int
     */
    private     $_success_query;

    /**
     * Количество запросов выполненных с ошибками
     * @var int
     */
    private     $_error_query;

    /**
     * Переключатель режима профилирования запросов
     * @var bool
     */
    private     $_use_timers;

    /**
     * Содержит массив запросов и время их выполнения
     * @var array
     */
    private     $_query_info;


}