<?php

include('GetCaratMetaController.php');
include('GetGroupController.php');
include('GetContentResultController.php');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GetDocsGeneralByTypeController
 *
 * @author aguilangeles@gmail.com
 */
class GetDocsGeneralByTypeController extends Controller {
	

    function GetDocsGeneralByTypeController() {
        
    }

    public function getDocsGeneralByType($c, $carats, $ocrs, $docsLevel1 = null, $currentdoc = null, $iddoc) {
        $content = "";
        $offset = $c->getOffset();
        foreach ($iddoc as $docl) {
            if (isset($currentdoc)) {
                if ($currentdoc == $docl) {
                    $c->setOffset($offset);
                } else {
                    $c->setOffset(0);
                }
            }
            $d = array($docl => 'doc');
            $docType = DocTypes::model()->findByPk($docl);

            $getCrtMeta = new GetCaratMetaController();
            $caratList = $getCrtMeta->getCaratMeta($d);
            $fields = array();
            foreach ($caratList as $caratM) {
                array_push($fields, Field::getField($caratM, 'CARAT', $docType->doc_type_desc));
            }
            $c->addCond('docType', '==', $docType->doc_type_desc);
	    
            $currentPage = ($c->getOffset() == 0) ? 1 : (($c->getOffset() / Idc::PAGE_SIZE) + 1);
	    
            $getGroup = new GetGroupController();
            $group = $getGroup->getGroup($c, $docType, $ocrs, $docType->doc_type_level, 'carat', $fields);
            $result = new GetContentResultController();
            $content = $content . $result->getContentResult($group, $currentPage, $docType->doc_type_level, $fields, $docType->doc_type_label, '/getResults/results_general' );
        }
        return $content;
    }

}
