<?php defined('SHU_CORED') or die('Access denied.');
/**
 * Created by PhpStorm.
 * User: tyva
 * Date: 29.06.19
 * Time: 21:57
 *
 */


class mainContentController extends absAdvContentController{

    public function default_handler(){

        $this->setView('default');
        /** @var mainGlobalModel $model */
        $model = $this->getModel("global");
        $videos = $model->getFrontpageVideos();
        $this->setData($videos);

    }

}