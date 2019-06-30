<?php defined('SHU_CORED') or die('Access denied.');
/**
 * Created by PhpStorm.
 * User: tyva
 */


class playerGlobalModel {


    public function getVideoById($id){

        $db = SS::getDB();
        $query = "SELECT `id`,`videoName`,`videoLink`,`videoCategory`,`videoType`,`link` FROM `videos`
                  as t JOIN `surdos` as b USING(id)
                  WHERE t.id = '$id' AND b.video = '$id'";
        $db->query($query);
        $result = $db->loadRow();
        return $result;


    }


    public function createRequest($request){

        $db = SS::getDB();
        $name = $request['vname'];
        $link = $request['vlink'];
        $cat = $request['vcat'];

        $query = "INSERT INTO `videos` VALUES(NULL,'$name','$link','$cat',2)";
        $db->query($query);

        return true;

    }



}