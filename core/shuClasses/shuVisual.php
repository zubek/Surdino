<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : shuVisual.php
 * Type  : core
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * 
 * Description:
 * Класс отвечает за визуализацию страницы
 * К моменту вызова функции startVisual, объект shuRenderMap
 * содержит все объекты участвующие в выводе страницы с подготовленными
 * данным.
 *
 * Данный класс перебирает все объекты и в зависимости от типа вывода и типа
 * объекта отвечающего за вывод применяет соответствующие методы визуализации.
 *
 */
class shuVisual{

    public function startVisual(){

        $rm = SS::getRenderMap();
        $rm->begin();

        while($element = $rm->getNext()){

            if($element->type() == 'static') {
                echo $element->data();
                continue;
            };

            switch($element->obj_type()){

                case 'content':
                {
                    $this->visual_content($element);
                    break;
                }

                case 'block':
                {
                    $this->visual_block($element);
                    break;
                }

                    default:
                        {
                            throw new excSystemError("Не известный тип объекта отображения [".$element->obj_type()."]");
                        }
            }

        }
    }

    /**
     * Отвечает за визуализацию контента
     * @param absContentController $obj
     * @throws excFileExistsError
     */
    private function visual_content($obj){

        $content_name = $obj->name();
        $content_dir  = CONTENTS_PATH.'/'.$content_name;

        $view_name    = $obj->View();

        $view_file = TEMPLATE_DIR.'/'.'contents'.'/'.$content_name.'/'.'views'.'/'.$view_name.'.php';
        if(!file_exists($view_file)) $view_file = $content_dir.'/'.'views'.'/'.$view_name.'.php';
        if(!file_exists($view_file)){

            throw new excFileExistsError($view_file);
        };

        $nots = SS::getNotes();
        if($nots->have('content_'.$obj->name())) $o_notes = $nots->getNotes('content_'.$obj->name());
        else $o_notes = null;
        visual($obj->data(), $view_file, $o_notes);
    }

    /**
     * @param absBlockController $obj
     */
    private function visual_block($obj){

        $block_name = $obj->name();
        $block_dir  = BLOCKS_PATH.'/'.$block_name;

        $view_name  = $obj->View();
        if($view_name === null) return;

        $view_file = TEMPLATE_DIR.'/'.'blocks'.'/'.$block_name.'/'.'views'.'/'.$view_name.'.php';
        if(!file_exists($view_file)) $view_file = $block_dir.'/'.'views'.'/'.$view_name.'.php';
        if(!file_exists($view_file)){
            //TODO: Логирование ошибки
            return;
        }

        $nots = SS::getNotes();
        if($nots->have('block_'.$obj->name())) $o_notes = $nots->getNotes('block_'.$obj->name());
        else $o_notes = null;
        visual($obj->data(), $view_file, $o_notes);
    }
}

/**
 * Подключает вид объекта и собственно в этом месте он и выводится
 *
 * @param mixed $data
 * @param string $view_file
 * @param array $notes
 */
function visual(&$data, $view_file, $notes = null){

    $doc = SS::getDocument();
    require $view_file;
}