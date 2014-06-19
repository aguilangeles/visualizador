<?php
include('GetDocsGeneralByTypeController.php');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SearchGeneralDoc
 *
 * @author aguilangeles@gmail.com
 */
class SearchGeneralDocController extends Controller {

    public function actionSearchGeneralDoc($currentPage = 1, $currentdoc = null) {
        $c = new EMongoCriteria;
        $conditions = array();
        if (!Yii::app()->user->isAdmin) {
            $c->addCond('visibleCarat', '==', TRUE);
            $condition = new Condition('visibleCarat', '==', TRUE);
            array_push($conditions, $condition);
            $c->addCond('visiblePapel', '==', TRUE);
            $condition = new Condition('visiblePapel', '==', TRUE);
            array_push($conditions, $condition);
            $c->addCond('visibleImagen', '==', TRUE);
            $condition = new Condition('visibleImagen', '==', TRUE);
            array_push($conditions, $condition);
        }
        $currentPage = $_POST['page'];
        $currentdoc = $_POST['docType'];
        $docsLevel1 = Users::getAllDocTypes((int) Yii::app()->user->id, 1);
        $ocrs = null; //$this->getOcrMeta($docsLevel1);
        $carats = null; //$this->getCaratMeta($docsLevel1);
        $searchType = $_POST['searchType'];
        $searchText = $_POST['field'];
        $docs = explode(',', $_POST['docs']);
        $arraydocs = "";
        $hasSpecialField = false;
        foreach ($docs as $doc) {
            $fields = array();
            $docType = DocTypes::model()->findByPk($doc);
            $arraydocs = $arraydocs . $docType->doc_type_desc . ',';
            foreach ($docType->Carats as $carat) {
                array_push($fields, Field::getField($carat->carat_meta_desc, 'CARAT', $docType->doc_type_desc));
                if ($carat->is_special) {
                    $hasSpecialField = TRUE;
                    if ($searchType == "Parecida") {
                        $query = new MongoRegex('/' . $searchText . '/i');
                        $c->addCond('CMETA_' . $carat->carat_meta_desc, 'or', $query);
                    } else {
                        $c->addCond('CMETA_' . $carat->carat_meta_desc, 'or', $searchText);
                    }
                }
            }
            foreach ($docType->OCRs as $ocr) {
                if ($ocr->is_special) {
                    $hasSpecialField = TRUE;
                    if ($searchType == "Parecida") {
                        $query = new MongoRegex('/' . $searchText . '/i');
                        $c->addCond('OCR_' . $ocr->ocr_meta_desc, 'or', $query);
                        $condition = new Condition('OCR_' . $ocr->ocr_meta_desc, 'regex', $searchText);
                    } else {
                        $c->addCond('OCR_' . $ocr->ocr_meta_desc, 'or', $searchText);
                        $condition = new Condition('OCR_' . $ocr->ocr_meta_desc, 'or', $searchText);
                    }
                    array_push($conditions, $condition);
                }
            }
        }
        $arraydocs = explode(',', $arraydocs);
        $c->addCond("docType", 'in', $arraydocs);
        $condition = new Condition("docType", 'in', $arraydocs);
        array_push($conditions, $condition);
        $c->limit(Idc::PAGE_SIZE);
        $c->offset(($currentPage - 1) * Idc::PAGE_SIZE);
        if ($hasSpecialField) {
            echo $this->getDocsGeneralByType($c, $docType, $conditions, $docs, $currentdoc);
        } else {
            echo '<div class="errorMessage"><img src="../images/error.png" style="height:25px;margin-bottom:-6px;"> No hay definido ningún campo especial. Por favor, configure un campo especial antes de usar esta búsqueda.</div>';
        }
    }
  private function getDocsGeneralByType($c, $carats, $ocrs, $docsLevel1 = null, $currentdoc = null) {
        $content = "";
        $offset = $c->getOffset();
        foreach ($docsLevel1 as $docl) {
            if (isset($currentdoc)) {
                if ($currentdoc == $docl) {
                    $c->setOffset($offset);
                } else {
                    $c->setOffset(0);
                }
            }
            $d = array($docl => 'doc');
            $docType = DocTypes::model()->findByPk($docl);


            $caratList = $this->getCaratMeta($d);
            $fields = array();
            foreach ($caratList as $caratM) {
                array_push($fields, Field::getField($caratM, 'CARAT', $docType->doc_type_desc));
            }
            $c->addCond('docType', '==', $docType->doc_type_desc);
            $currentPage = ($c->getOffset() == 0) ? 1 : (($c->getOffset() / Idc::PAGE_SIZE) + 1);
            $getGroup = new GetGroupController();

            $group = $getGroup->getGroup($c, $docType, $ocrs, $docType->doc_type_level, 'carat', $fields);
            

            $content = $content . $this->getContentResult($group, $currentPage, $docType->doc_type_level, $fields, 'results_general');
        }
        return $content;
    }

}
