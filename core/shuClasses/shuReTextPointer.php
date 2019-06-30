<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : shuReTextPointer.php
 * Type  : core
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * Description:
 * Класс реализует элемент рендеринга: Указатель на текст.
 * При создании экземпляра класса, в его конструктор передается указатель на текстову переменную,
 * который в последствии будет выведен в конвеере рендеринга.
 */
class shuReTextPointer extends absStaticRenderElement{

    /**
     * Конструктор
     * @param string $text
     */
    function __construct(&$text){
        $this->_data =& $text;
    }

    /**
     * Возвращает данные для отображения
     * @return string
     */
    public function &data(){
        return $this->_data;
    }

    /**
     * Хранит данные для отображения
     * @var string
     */
    private $_data;
}