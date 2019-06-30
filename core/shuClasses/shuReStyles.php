<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : shuReStyles.php
 * Type  : core
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * Description:
 */
class shuReStyles extends absStaticRenderElement{

    /**
     * Возвращает блок HTML разметки включающей все стили документа
     * @return string
     */
    public function &data(){
        $result = '';
        $elems = count($this->_styles);
        for($i = 0; $i < $elems; $i++){

            if($this->_styles[$i][0] == '/'){
                $s_file = BASE_URL.$this->_styles[$i];
            }else{
                $s_folder = TEMPLATE_URL.'css/';
                $s_file = $s_folder.$this->_styles[$i];
            };
            $result .= '<link href="'.$s_file.'" type="text/css" rel="stylesheet" />'."\n";
        }

        return $result;
    }

    /**
     * Добавляет файл стиля к документу
     * @param string $file
     */
    public function addStyle($file){
        if(!is_array($this->_styles)) $this->_styles = array();
        if(array_search($file,$this->_styles) === false) $this->_styles[] = $file;
    }

    /**
     * Массив хранит файлы стилей, которые должны быть подключены к документу
     * @var array
     */
    private $_styles;
}