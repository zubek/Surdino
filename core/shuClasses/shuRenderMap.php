<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : shuRenderMap.php
 * Type  : core
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * Description:
 *
 * Класс реализует очередь конвеера рендеринга.
 * Все объекты для отображения (реализуют интерфейс IntRenderElement) добавляются
 * в очередь, а затем на этапе визуализации извлекаются и отображаются.
 */
class shuRenderMap{

    function __construct(){
        $this->_elements        = array();
        $this->_count           = 0;
        $this->_current_element = 0;
    }

    /**
     * Добавляет в очередь рендеринга объект рендеринга
     *
     * @param $obj IntRenderElement
     */
    public function addToMap($obj){

        $buf = ob_get_clean();

        if($buf !== ''){

            $text_object = new shuReText($buf);
            $this->append($text_object);
        };

        ob_start();

        $this->append($obj);
    }

    public function addFinal(){

        $buf = ob_get_clean();

        if($buf !== ''){

            $text_object = new shuReText($buf);
            $this->append($text_object);
        };

        ob_start();
    }

    /**
     * Устанавливает указатель текущего элемента на начало очереди
     */
    public function begin(){
        $this->_current_element = 0;
    }

    /**
     * Устанавливает указатель текущего элемента на конец очереди
     */
    public function end(){
        $this->_current_element = $this->_count;
    }

    /**
     * Возвращает текущий элемент в очереди и смещает указатель элмента
     * на одну позицию. Возвращает null , если достигнут конец очереди либо
     * если список пуст (что одно и тоже)
     *
     * @return null|absBlockController|absContentController|absStaticRenderElement
     */
    public function getNext(){

        if($this->_current_element == $this->_count) return null;

        $obj = $this->_elements[$this->_current_element];
        $this->_current_element++;
        return $obj;
    }

    /**
     * Добавляет в конец очередной элемент рендеринга и увеличивает счетчик элементов
     *
     * @param $obj IntRenderElement
     */
    private function append($obj){

        $this->_elements[] = $obj;
        $this->_count++;
    }

    /**
     * Содержит массив элементов для вывода документа
     * @var IntRenderElement[]
     */
    private $_elements;

    /**
     * Содержит количество элементов рендеринга в очереди
     * @var int
     */
    private $_count;

    /**
     * Указатель на текущий элемент в очереди.
     * Используется для итераций
     * @var int
     */
    private $_current_element;
}