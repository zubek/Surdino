<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File  : shuApp.php
 * Type  : core
 * Autor : Anton Tarkhanov (Shude)
 * E-mail: imkekx@gmail.com
 * Description:
 */
class shuApp{

    function __construct(){

        if(SS::getSettings()->use_timers === true) $this->_start_time = microtime(true);

        #Включаем буферизацию вывода (самый нижний уровень)
        ob_start();
    }

    /**
     *
     */
    public function run(){

        $h = new shuExceptionHandler();
        try{

            $this->step_preload();
            $this->step_render();
            $this->step_final();

        }catch(excDbError $e){
            $h->dbError($e);
        }catch(excContentError $e){
            $h->contentError($e);
        }catch(excFileExistsError $e){
            $h->fileExistsErorr($e);
        }catch(excPermissionDenied $e){
            $h->permissionDenied($e);
        }catch(excRequestError $e){
            $h->requestError($e);
        }catch(excSystemError $e){
            $h->systemError($e);
        }

    }

    /**
     * @throws excFileExistsError
     */
    private function step_preload(){

        #За вывод содержимого всегда отвечает контроллер контента,
        #поэтому первым делом нужно получить объект этого контроллера

        $req  = SS::getRequest();

        #После того, как создан объект Request , системе известно в каком режиме
        #мы находимся (пользовательский или административный)
        #Определим константы путей

        $opt = SS::getSiteOptions();

        $user = SS::getUser();

        $layout = null;

        if(defined('ADMIN_MODE')){

            define('CONTENTS_PATH'  , ADMIN_PATH.'/contents');
            define('BLOCKS_PATH'    , ADMIN_PATH.'/blocks');

            if(!$user->isAuth()) $this->redirect('/user/login/', false);
            if(!$user->perms->sysPerm('admin','admin_entry')) throw new excPermissionDenied();

        }else{

            define('CONTENTS_PATH'  , ROOT_PATH.'/contents');
            define('BLOCKS_PATH'    , ROOT_PATH.'/blocks');

            if(defined('FRONTPAGE')) $layout = $opt->sysOpt('frontpage_layout');
            if($layout === null) $layout = $opt->contentOpt($req->controller(), 'custom_layout');

        }

        #Получим название темы
        defined('ADMIN_MODE') ? $tvar = 'admin_template' : $tvar = 'main_template';
        $template_name = $opt->sysOpt($tvar);

        #Определим соответствующие константы
        define('TEMPLATE_NAME',$template_name);
        $template_dir   = TEMPLATES_PATH.'/'.$template_name;
        define('TEMPLATE_DIR',$template_dir);

        $template_entry = ($layout === null) ? $template_name.'.php' : $layout.'.php';
        $template_file = $template_dir.'/'.$template_entry;

        define('TEMPLATE_FILE',$template_file);
        define('TEMPLATE_URL',BASE_URL.'/tpls/'.TEMPLATE_NAME.'/');
        define('IMG_URL', TEMPLATE_URL.'imgs/');

        if(!file_exists(TEMPLATE_FILE)){
            throw new excFileExistsError(TEMPLATE_FILE);
        }

    }

    private function step_render(){

        $req = SS::getRequest();
        if($req->controller() == 'ajax') SS::getDocument()->content();
        else template_process();

        #В данный момент в объекте shuRenderMap содержатся все объекты
        #участвующие в выводе страницы. Теперь для всех динамических эелементов
        #нам требуется вызвать метод build() , чтобы сгенерировать динамические данные.

        $rm = SS::getRenderMap();

        #Метод addFinal , добавляет в контекст рендеринга данные которые на данный момент остались в буфере
        $rm->addFinal();
        #Установим указатель на первый элемент рендеринга
        $rm->begin();

        while($r_obj = $rm->getNext()){

            #Если элемент динамический, вызовем метод build , для генерации данных
            if($r_obj->type() == 'dynamic') $r_obj->build();
        };

        /**
         * TODO:  Здесь можно добавить вызов пользовательских методов
         *        для тго чтобы они могли повлиять на исходные данные перед
         *        стадией визуализации
         */

        #Стадия визуализации
        $visual_object = SS::getVisual();
        $visual_object->startVisual();

    }

    private function step_final(){

        $out_data = ob_get_clean();

        echo str_replace('{shu_debug_info_position}',$this->get_debug_info(),$out_data);
    }

    private function get_debug_info(){

        $app_time = microtime(true) - $this->_start_time;
        $app_time = number_format($app_time,6,'.','');
        $db = SS::getDB();

        $infos = $db->queryTimers();
        $total_query_time = number_format($db->execTime(),6,'.','');

        $result = "<div id='debug_info'>Время сборки страницы: $app_time | Время SQL запросов: $total_query_time"
                 ."<table>";

        foreach($infos as $val){

            $result .= '<tr><td>'.$val['query'].'</td><td>'.number_format($val['time'],6,'.','').'</td></tr>';
        };

        $result .= '</table></div>';

        return $result;
    }

    /**
     * Статичный метод. Выполняет переадресацию пользователя на
     * заданный URL
     *
     * @param string $loc
     * @param bool $auto_mode
     */
    public static function redirect($loc, $auto_mode = true){

        if(!strstr($loc,"http://") && !strstr($loc,"https://")){

            if(defined('ADMIN_MODE') && $auto_mode){

                $set = SS::getSettings();
                $url = BASE_URL.'/'.$set->admin_tag.$loc;

            }else $url = BASE_URL.$loc;

        }else $url = $loc;

        while (ob_get_level()) {
            ob_end_clean();
        };

        header('Location: '.$url);
        exit();
    }

    /**
     * Производит 301 переадресацию
     *
     * @param $new_url string
     */

    public static function move_permanent($new_url){

        if(!strstr($new_url,"http://")) $url = BASE_URL.$new_url;
        else $url = $new_url;

        while (ob_get_level()) {
            ob_end_clean();
        };

        header('HTTP/1.1 301 Moved Permanently');
        header('Location: '.$url);
        exit();
    }

    /**
     * Хранит временную метку старта приложения (если в настройках use_timers установлено в true)
     * @var float
     */
    private $_start_time;
}



/**
 * Функция подключает файл шаблона, с чего собственно и начинается все волшебство.
 * Данная функция вынесена за пределы класса, для того чтобы ограничить область
 * видимости внутри шаблона.
 * Таким образом в шаблоне у нас доступен экземпляр класса shuDocument , с помощью
 * которого происходит весь вывод содержимого.
 */
function template_process(){

    #Избегаем случайного повторного вызова функции.
    #Так как функция определена вне класса, существует вероятность,
    #что она может быть непреднамеренно вызвана.

    static $_started = false;
    if($_started === true) return;
    if($_started === false) $_started = true;

    $doc = SS::getDocument();
    require TEMPLATE_FILE;

}