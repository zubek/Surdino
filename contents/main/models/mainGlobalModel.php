<?php defined('SHU_CORED') or die('Access denied.');
/**
 * Created by PhpStorm.
 * User: tyva
 *
 *
 */

class mainGlobalModel{

    public function getFrontpageVideos(){

        $db = SS::getDB();
        $query = "SELECT * FROM `videos`";
        $db->query($query);
        $result = $db->loadRowList();
        return $result;

    }


    public function insertRequest(){

        $db = SS::getDB();
        $query = "";

    }

}