<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ToggleImageVisibilityController
 *
 * @author aguilangeles@gmail.com
 */
class ToggleImageVisibilityController extends Controller{
    
     public function actionToggleImageVisibility() {
        $message = '';
        $action = $_POST["action"];
        $currIndex = $_POST["currIndex"];
        $imageList = get_object_vars(json_decode($_POST["imageList"]));
        $image = $imageList['images'][$currIndex];
        $ids = array();
        array_push($ids, new MongoId($image->id));
        foreach ($image->reverseImage as $img) {
            array_push($ids, new MongoId($img->id));
        }
        foreach ($ids as $imageId) {
            $c = new EMongoCriteria;
            //$theObjId = new MongoId($image->id); 
            $c->addCond('_id', '==', $imageId);
            $modifier = new EMongoModifier();
            if ($action == "show") {
                $modifier->addModifier('visibleImagen', 'set', TRUE);
                if (Idc::model()->updateAll($modifier, $c)) {
                    $message = "La imagen es visible";
                }
            } else {
                $modifier->addModifier('visibleImagen', 'set', FALSE);
                if (Idc::model()->updateAll($modifier, $c)) {
                    $message = "Se ocultó la imagnen ";
                }
            }
        }
        echo $message;
    }
}
