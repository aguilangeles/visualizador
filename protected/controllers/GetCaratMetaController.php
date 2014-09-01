<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GetCaratMetaController
 * noname@noname
 */
class GetCaratMetaController extends Controller {

    function GetCaratMetaController() {
        
    }

    public function getCaratMeta($docs) {
        $docs = array_flip($docs);
        $caratMeta = array();
        foreach ($docs as $doc) {
            $document = DocTypes::model()->findByPk($doc);
            foreach ($document->Carats as $carat) {
                $caratMeta = $caratMeta + array($carat->carat_meta_id => $carat->carat_meta_desc);
            }
        }
        return $caratMeta;
    }

}
