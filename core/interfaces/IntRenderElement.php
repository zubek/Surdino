<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : IntRenderElement.php
 * Type  : interface
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * Description:
 * Интерфейс для объектов рендеринга.
 * Любой класс реализующий этот интерфейс, должен быть абстрактным.
 * Помимо этого, метод type() должен быть реализован в этом классе
 * и иметь префикс final.
 *
 * Если в классе реализующем данный интерфейс метод type возвращает
 * строку 'dynamic' , то в классе должен обязательно быть определен
 * метод build()
 */

interface IntRenderElement{

    /**
     * Метод должен возвращать один из двух вариантов:
     * 'static' или 'dynamic'
     *
     * Если класс возвращает тип как 'static' , то это говорит о том,
     * что для вывода содержимого классу не требуется вызов метода build
     * для подготовки данных. В этом случае метод data() возвращает просто текст
     * для вывода и не имеет представления.
     *
     * В случае если класс возвращает 'dynamic' , то значит перед выводом содержимого
     * необходимо вызвать метод build(). В этом случае метод data() будет возвращать массив
     * подготовленных данных для использовании в представлении.
     *
     * Возвращает тип вывода элемента
     * @return string
     */
    public function type();

    /**
     * Возвращает тип объекта
     * @return string
     */
    public function obj_type();

    /**
     * Возвращает данные для вывода.
     *
     * @return mixed
     */
    public function &data();

    /**
     * Реализуется классами с типом 'dynamic', для классов типа 'static'
     * должна возвращать true
     *
     * @return bool
     */
    public function build();
}