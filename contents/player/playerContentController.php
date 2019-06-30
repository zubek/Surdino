<?php defined('SHU_CORED') or die('Access denied.');
/**
 * Created by PhpStorm.
 * User: tyva
 */

class playerContentController extends absAdvContentController{

    public function default_handler(){

        $this->setView('default');
        SS::getDocument()->addScript('surdino_player.js');

    }

    public function page_video(){

        $req = SS::getRequest();
        $video_id = $req->param(1);
        SS::getDocument()->addScript('surdino_player.js');
        /** @var playerGlobalModel $model */
        $model = $this->getModel("global");
        $data = $model->getVideoById($video_id);
        $this->setData($data);
        $this->setView('default');
        $debug = "debug";

    }

    public function page_request(){

        $this->setView('request');

    }

    public function page_request_action_request(){

        $req = SS::getRequest();
        $request = array();

        $request["vlink"] = $req->post("vlink","raw");
        $request["vname"] = $req->post("vname","raw");
        $request["vcat"] = $req->post("vcat","raw");
        //TODO: Валидация входных данных
        /** @var playerGlobalModel $model */
        $model = $this->getModel("global");

        $model->createRequest($request);

        $debug = "debug";

        shuApp::redirect("/");

    }

}