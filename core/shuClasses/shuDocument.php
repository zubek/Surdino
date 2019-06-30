<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : shuDocument.php
 * Type  : core
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * Description:
 * Класс Web-документа. Данный класс реализует методы для вывода
 * объектов на страницу.
 *
 * На самом деле класс shuDocument не производит никакого вывода,
 * в этом суть реализации всей системы SHUCored. Класс shuDocument
 * создает экземпляры всех объектов, которые будут участвовать в выводе
 * содержимого и складывает эти объекты в очередь рендеринга (shuRenderMap)
 * в той последовательности в которой они должны быть отображены.
 *
 * В результате разработчик внутри шаблона может работать с методами
 * shuDocument так, как-будто они выводят содержимое, но благодаря,
 * тому что этот вывод будет отложенным, появляется возможность манипулировать
 * данными подготовленными к выводу в любом месте кода до момента визуализации.
 *
 * Например: Если в шаблоне сначала вызвать метод $doc->styles(); а потом
 *           где-то далеко в конце воспользоваться методом $doc->addStyle(),
 *           то это не будет ошибкой и более того добавленный файл стиля
 *           в конце шаблона будет выведен в начале где ему и предназначено.
 *
 */

class shuDocument{

    function __construct(){
        $this->_styles_list = new shuReStyles();
        $this->_scripts_list = new shuReScripts();
        $this->_title = SS::getSiteOptions()->sysOpt('default_title');

        $this->load_positions(TEMPLATE_NAME);

        //$this->addStyle('/media/css/bootstrap.min.css');
        //$this->addScript('/media/js/jquery-2.0.3.js');
    }

    /**
     * Метод используется в файле шаблона для вывода содержимого контента
     */
    public function content(){

        $req  = SS::getRequest();

        #Проверим права у пользователя на доступ к контенту.
        #Если прав нет, то нужно перекинуть его на страницу авторизации.

        defined('ADMIN_MODE') ? $access = 'admin_access' : $access = 'access';

        $ctrl = SS::getContentController($req->controller());

        $user = SS::getUser();
        if(!$user->perms->contentPerm($req->controller(),$access)){

            throw new excPermissionDenied();
        };

        $rm   = SS::getRenderMap();

        $rm->addToMap($ctrl);
    }

    /**
     * Метод используется в файле шаблона для вывода отладочной информации
     * Замечание: Если в настройках приложения параметр debug_mode не установлен
     * в значение true , то данный блок будет проигнорирован.
     */
    public function debug_info(){

        if(SS::getSettings()->debug_mode !== true) return;
        $rm  = SS::getRenderMap();
        $obj = new shuReText("{shu_debug_info_position}");
        $rm->addToMap($obj);
    }

    /**
     * Выводит тег img с заданными параметрами
     *
     * @param string $name
     * @param number $w
     * @param number $h
     * @param string $alt
     */

    public function img($name, $w, $h, $alt){
        echo '<img src="'.IMG_URL.$name.'" width="'.$w.'" height="'.$h.'" alt="'.$alt.'" />';
    }

    /**
     * Выводит атрибут src для img
     *
     * @param string $name
     */

    public function img_src($name){
        echo IMG_URL.$name;
    }

    /**
     * Метод используется в файле шаблона для вывода блоков в определенной позиции
     *
     * @param null|string $pos_name
     */
    public function position($pos_name = null){

        if(!$pos_name) return;

        $wk =& $this->_positions[$pos_name];
        if(!is_array($wk)) return;

        $rm = SS::getRenderMap();

        foreach($wk as $el){

            $user = SS::getUser();

            defined('ADMIN_MODE') ? $access = 'admin_access' : $access = 'access';

            if(!$user->perms->blockPerm($el['block_cntrl'],$access)) continue;
            $bc = SS::createBlockController($el['block_cntrl'], $el['vars']);
            if($bc === null) continue;

            $rm->addToMap($bc);
        }
    }

    /**
     * Добавляет файл стиля к документу
     * @param string $file
     */
    public function addStyle($file){

        $this->_styles_list->addStyle($file);
    }

    /**
     * Добавляет стиль в динамический вывод
     * @param $text string  (корректный CSS блок кода)
     */
    public function addDynamicStyle($text){
        $this->_dynamic_styles .= "\n$text";
    }

    /**
     * Добавляет файл JavaScript к документу
     * @param string $file
     */
    public function addScript($file){

        $this->_scripts_list->addScript($file);
    }

    /**
     * Устанавливает новое значение заголовка.
     * Если в новом значении заголовка содержится макросс {title},
     * то он будет заменен на старое значение заголовка.
     *
     * @param string $text
     */
    public function setTitle($text){

        $older = $this->_title;
        $this->_title = str_replace('{title}',$older,$text);
    }

    /**
     * Устанавливает новое значение Description для страницы
     * @param $text string
     */
    public function setDescription($text){
        $this->_description = $text;
    }

    /**
     * Метод импользуется в шаблонах для вывода стилей документа
     */
    public function styles(){

        $rm = SS::getRenderMap();
        $rm->addToMap($this->_styles_list);
    }

    /**
     * Метод выводит динамические стили
     */
    public function dynamic_styles(){

        $obj = new shuReTextPointer($this->_dynamic_styles);
        $rm = SS::getRenderMap();
        $rm->addToMap($obj);
    }

    /**
     * Метод используется в шаблонах для вывода всех файлов JavaScript
     */
    public function scripts(){

        $rm = SS::getRenderMap();
        $rm->addToMap($this->_scripts_list);
    }

    public function title(){

        $obj = new shuReTextPointer($this->_title);
        $rm  = SS::getRenderMap();
        $rm->addToMap($obj);
    }

    public function description(){

        if($this->_description === null){
            $this->_description = SS::getSiteOptions()->sysOpt('default_description');
        }
        $obj = new shuReTextPointer($this->_description);
        $rm  = SS::getRenderMap();
        $rm->addToMap($obj);
    }

    /**
     * Получает информацию о всех позициях заданного шаблона
     * @param string $template_name
     */
    private function load_positions($template_name){

        $db = SS::getDB();

        $query = "SELECT `name`,`block_cntrl`,`weight`,`out_id`,`vars`
                      FROM `shu_positions`
                      WHERE `theme` = '$template_name'
                      ORDER BY `name`,`weight` ASC";
        $db->query($query);
        $this->_positions = array();
        while($row = $db->loadRow()){

            $data_block = array();
            $data_block['block_cntrl'] = $row['block_cntrl'];
            $data_block['out_id'] = $row['out_id'];
            $data_block['vars'] = json_decode($row['vars'],true);

            $this->_positions[$row['name']][] = $data_block;
        };
    }

    /**
     * Объект отвечает за список файлов стилей
     * @var shuReStyles
     */
    private $_styles_list;

    /**
     * Объект отвечает за список файлов скриптов
     * @var shuReScripts
     */
    private $_scripts_list;

    /**
     * Заголовок страницы
     * @var string
     */
    private $_title;

    /**
     * Содержит описание страницы (meta description)
     * @var string|null
     */
    private $_description = null;

    /**
     * Хранит информацию о всех позициях текущего шаблона
     * @var array
     */
    private $_positions;

    /**
     * Хранит динамические стили
     * @var string
     */
    private $_dynamic_styles;
}