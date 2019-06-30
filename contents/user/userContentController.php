<?php defined('SHU_CORED') or die('Access denied.');
/**
 * File    : userContentController.php
 * Author  : Anton Tarkhanov
 * Email   : imkekx@gmail.com
 * Type    : controller
 * 
 * Description:
 */
class userContentController extends absContentController{

    public function build(){

        $req = SS::getRequest();
        $opt = $req->param(0);

        switch($opt){

            case 'login':
            {
                $this->login_proc();
                break;
            }

            case 'logout':
            {
                $sess = SS::getSession();
                $sess->destroy();
                shuApp::redirect('/');
                break;
            }
            default:
                {
                    throw new excRequestError('Не верный адрес запроса');
                    break;
                }
        }
    }

    private function login_proc(){

        $req    = SS::getRequest();
        $action = $req->post('action','enword');

        $user = SS::getUser();
        if($user !== null && $user->isAuth() === true) shuApp::redirect(BASE_URL);

        if($action === null){

            SS::getDocument()->setTitle('Авторизация');
            $this->setView('login');
            return;

        }else if($action == 'auth'){

            $login = $req->post('login','login');
            $pass  = $req->post('password','password');
            if(!$login || !$pass){

                $nots = SS::getNotes();
                $nots->setNote('content_user','err_msg','Введено недопустимое значение');
                shuApp::redirect('/user/login/');
            };

            $user = new shuUser();

            if($user->load($login, $pass) === false){
                $nots = SS::getNotes();
                $nots->setNote('content_user','err_msg',$user->errMsg());
                unset($user);
                shuApp::redirect('/user/login/');
            };

            $sess = SS::getSession();
            $sess->setVar('shu_auth_user', true);
            $sess->setVar('shu_user_id',$user->uid);

            shuApp::redirect(BASE_URL);

        };

        throw new excRequestError('Не верный формат запроса');
    }
}