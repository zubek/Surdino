<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : shuReText.php
 * Type  : core
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * Description:
 * Класс реализует элемент рендеринга: Текст.
 * При создании экземпляра класса, в его конструктор передается текст,
 * который в последствии будет выведен в конвеере рендеринга.
 */
class shuReText extends absStaticRenderElement{

    /**
     * Конструктор
     * @param string $text
     */
    function __construct($text){
        $this->_data = $text;
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