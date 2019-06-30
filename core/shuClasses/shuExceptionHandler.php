<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File    : shuExceptionHandler.php
 * Author  : Anton Tarkhanov
 * Email   : imkekx@gmail.com
 * Type    : core
 * 
 * Description:
 */
class shuExceptionHandler{

    /**
     * @param excDbError $e
     */
    public function dbError($e){

        $f = $this->getErrorTemplate('db_error.php');
        if(!$f) return;
        $d = array('error_text' => $e->getMessage(),
                   'debug_info' => $this->get_debug_info($e));
        $this->out_error($f, $d);
    }

    /**
     * @param excContentError $e
     */
    public function contentError($e){

        $f = $this->getErrorTemplate('content_error.php');
        if(!$f) return;
        $d = array('error_text' => $e->getMessage(),
            'debug_info' => $this->get_debug_info($e));
        $this->out_error($f, $d);
    }

    /**
     * @param excFileExistsError $e
     */
    public function fileExistsErorr($e){

        header("HTTP/1.0 404 Not Found");
        $f = $this->getErrorTemplate('file_exists_error.php');
        if(!$f) return;

        $di = $this->get_debug_info($e);

        $d = array('error_text' => $e->getMessage(),
                   'debug_info' => $di);
        $this->out_error($f, $d);
    }

    /**
     * @param excPermissionDenied $e
     */
    public function permissionDenied($e){

        $user = SS::getUser();
        if(!$user->isAuth())
        {
            $nots = SS::getNotes();
            $nots->setNote('user','permission','denied');
            shuApp::redirect('/user/login/');
        };

        $f = $this->getErrorTemplate('permission_denied.php');
        if(!$f) return;
        $d = array('error_text' => $e->getMessage(),
            'debug_info' => $this->get_debug_info($e));
        $this->out_error($f, $d);
    }

    /**
     * @param excSystemError $e
     */
    public function systemError($e){

        $f = $this->getErrorTemplate('system_error.php');
        if(!$f) return;
        $d = array('error_text' => $e->getMessage(),
            'debug_info' => $this->get_debug_info($e));
        $this->out_error($f, $d);
    }

    /**
     * @param excRequestError $e
     */
    public function requestError($e){

        header("HTTP/1.0 404 Not Found");
        $f = $this->getErrorTemplate('request_error.php');
        if(!$f) return;
        $d = array('error_text' => $e->getMessage(),
            'debug_info' => $this->get_debug_info($e));
        $this->out_error($f, $d);
    }

    /**
     * @param $rf       подключаемый файл шаблона ошибки
     * @param $data     массив данных об ошибке
     */
    private function out_error($rf,$data){

        while (ob_get_level()) {
            ob_end_clean();
        };

        require $rf;
    }

    private function getErrorTemplate($name){

        $set = SS::getSettings();
        $folder = $set->error_template;

        $file = ERROR_TPLS_PATH.'/'.$folder.'/'.$name;
        if(!file_exists($file)){
            $file = ERROR_TPLS_PATH.'/'.$folder.'/default.php';
            if(!file_exists($file)) return null;
        }

        return $file;
    }

    /**
     * @param Exception $e
     * @return null|array
     */
    private function get_debug_info($e){

        $set = SS::getSettings();
        if($set->debug_mode === false) return null;

        $info = array('file' => str_replace(DIRECTORY_SEPARATOR, '/',$e->getFile()),
                      'line' => $e->getLine());
        return $info;
    }
}