<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : absStaticRenderElement.php
 * Type  : abstract
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * 
 * Description:
 */
abstract class absStaticRenderElement implements IntRenderElement{
    /**
     * Возвращает тип элемента вывода
     * @see IntRenderElement
     * @return string
     */
    final public function type(){return 'static';}

    final public function obj_type(){return 'text';}

    final public function build(){return true;}
}