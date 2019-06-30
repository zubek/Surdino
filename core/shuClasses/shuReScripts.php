<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : shuReScripts.php
 * Type  : core
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * Description:
 */
class shuReScripts extends absStaticRenderElement{

    /**
     * Возвращает блок HTML разметки для включения всех JavaScript файлов
     * @return string
     */
    public function &data(){

        $result = '';
        $elems = count($this->_scripts);
        for($i = 0; $i < $elems; $i++){

            if(strstr($this->_scripts[$i],"http://")) $s_file = $this->_scripts[$i];
            else if($this->_scripts[$i][0] == '/'){
                $s_file = BASE_URL.$this->_scripts[$i];
            }else{
                $s_folder = TEMPLATE_URL.'js/';
                $s_file = $s_folder.$this->_scripts[$i];
            };

            $result .= '<script type="text/javascript" src="'.$s_file.'" charset="utf-8"></script>'."\n";
        }

        return $result;
    }

    /**
     * Добавляет в список файл скрипта для подключения к документу
     * @param string $file
     */
    public function addScript($file){
        if(!is_array($this->_scripts)) $this->_scripts = array();
        if(array_search($file,$this->_scripts) === false) $this->_scripts[] = $file;
    }

    /**
     * Содержит список скриптов для подключения к документу
     * @var array
     */
    private $_scripts;
}