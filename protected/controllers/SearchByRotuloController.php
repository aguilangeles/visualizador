<?php

include('GetGroupController.php');
include('GetContentResultController.php');
include('GetCaratMetaController.php');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SearchByRotuloController
 *
 * @author aguilangeles@gmail.com
 */
class SearchByRotuloController extends Controller {

    public function actionSearchByRotulo($currentPage = 1) {
        if (isset($_POST['rotulo'])) {
            $conditions = array();
            $c = new EMongoCriteria;
            $rotuloId = $_POST['rotulo'];
            $searchType = $_POST['searchType'];
            $currentPage = $_POST['page'];
            $rotulo = Rotulos::model()->findByPk($rotuloId);
            $fields = array();
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
            foreach ($rotulo->Docs as $doc) {
                $document = DocTypes::model()->findByPk($doc->doc_type_id);
                $c->addCond('docType', 'or', $document->doc_type_desc);
            }
            $getCrtMeta = new GetCaratMetaController();
            $carats = $getCrtMeta->getCaratMeta(array($doc->doc_type_id => $doc->doc_type_id));
            $i = 0;
            foreach ($carats as $caratM) {
                array_push($fields, Field::getField($caratM, 'CARAT', $document->doc_type_desc));
                $carat = CaratMeta::model()->find('carat_meta_desc = :desc', array(':desc' => $caratM));
                $campo = $carat->carat_meta_desc;
                if ($_POST['CMETA_'][$i] != '') {
                    if ($searchType == "Parecida") {
                        $query = new MongoRegex('/' . $_POST['CMETA_'][$i] . '/i');
                        $c->addCond('CMETA_' . $carat->carat_meta_desc, '==', $query);
                    } else {
                        $c->addCond('CMETA_' . $carat->carat_meta_desc, '==', $_POST['CMETA_'][$i]);
                    }
                }
                $i++;
            }

            $c->limit(Idc::PAGE_SIZE);
            $c->offset(($currentPage - 1) * Idc::PAGE_SIZE);
            $c->setSort(array('order', EMongoCriteria::SORT_ASC));
            echo $this->getDocsByRotulo($c, $document, $conditions, $fields);
        }
    }


    /**
     * Busqueda inicial por rótulo. Devuelve las caratulas.
     *
     */
    private function getDocsByRotulo($c, $carats, $ocrs, $fields, $docsLevel = 'c1') {
        $currentPage = ($c->getOffset() == 0) ? 1 : (($c->getOffset() / Idc::PAGE_SIZE) + 1);
        $getGroup = new GetGroupController();
        $group = $getGroup->getGroup($c, $carats, $ocrs, $docsLevel, 'carat', $fields);
        $getCResult = new GetContentResultController();
        return $getCResult->getContentResult($group, $currentPage, $docsLevel, $fields, '/getResults/results_rotulos');
    }

}
