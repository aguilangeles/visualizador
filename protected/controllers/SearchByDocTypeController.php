<?php

include('GetGroupController.php');
include('GetContentResultController.php');
/**
 * Búsqueda por tipo de documento.
 * @param int $currentPage
 * @return string
 * @author GDM
 */

/**
 * Description of SearchByDocTypeController
 *
 */
class SearchByDocTypeController extends Controller {

    public function actionSearchByDocType($currentPage = 1) {
        if (isset($_POST['docLevel1'])) {
            $carats = null;
            $conditions = array();
            $currentPage = $_POST['page'];
            $docLevel1 = (int) $_POST['docLevel1'];
            $docLevel2 = (int) $_POST['docLevel2'];
            $docLevel3 = (int) $_POST['docLevel3'];
            $docLevel4 = (int) $_POST['docLevel4'];
            $docs = array($docLevel1, $docLevel2, $docLevel3, $docLevel4);
            $searchType = $_POST['searchType'];
            $groupBy = $_POST['groupType'];
            $like = ($searchType == "Exacta") ? '' : '/';
            $docsLevels = array();
            $docsLevel = 'c1';
            for ($i = 0; $i < 4; $i++) {
                if ($docs[$i] != 0) {
                    $doc = DocTypes::model()->findByPk($docs[$i]);
                    $docsLevels = $docsLevels + array($doc->doc_type_id => $doc->doc_type_desc);
                    $docsLevel = "c" . $doc->doc_type_level;
                }
            }
            $content = '';
            foreach ($docsLevels as $doc) {
                $c = new EMongoCriteria;
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
                $fields = array();
                $docType = DocTypes::getDocumentByDocTypeDesc($doc);
                $c->addCond('docType', 'or', $doc);
                $condition = new Condition('docType', 'or', $doc);
                array_push($conditions, $condition);
                $carats = DocTypes::getMetaCarats($docType->doc_type_id);
                $i = 0;

                foreach ($carats as $caratM) {
                    if ($groupBy == 'carat') {
                        array_push($fields, Field::getField($caratM, 'CARAT', $doc));
                    }
                    $carat = CaratMeta::model()->find('carat_meta_desc = :desc', array(':desc' => $caratM));
                    $campo = $carat->carat_meta_desc;
                    if ($_POST['CMETA_'][$i] != '') {
                        if ($groupBy != 'carat') {
                            array_push($fields, Field::getField($caratM, 'CARAT', $doc));
                        }
                        if ($searchType == "Parecida") {
                            $query = new MongoRegex('/' . $_POST['CMETA_'][$i] . '/i');
                            $c->addCond('CMETA_' . $carat->carat_meta_desc, '==', $query);
                        } else {
                            $c->addCond('CMETA_' . $carat->carat_meta_desc, '==', $_POST['CMETA_'][$i]);
                        }
                    }
                    $i++;
                }
                $ocrsMeta = DocTypes::getMetaOcrs($docType->doc_type_id);
                $i = 0;
                foreach ($ocrsMeta as $ocrM) {
                    $ocr = OcrMeta::model()->find('ocr_meta_desc = :desc', array(':desc' => $ocrM));
                    $campo = $ocr->ocr_meta_desc;
                    if ($_POST['OCR_'][$i] != '') {
                        if ($groupBy == 'image') {
                            array_push($fields, Field::getField($ocr->ocr_meta_desc, 'OCR', $doc));
                        }
                        if ($searchType == "Parecida") {
                            $query = new MongoRegex('/' . $_POST['OCR_'][$i] . '/i');
                            $c->addCond('OCR_' . $ocr->ocr_meta_desc, '==', $query);
                            if ($groupBy == 'carat') {
                                $condition = new Condition('OCR_' . $ocr->ocr_meta_desc, 'regex', $_POST['OCR_'][$i]);
                                array_push($conditions, $condition);
                            }
                        } else {
                            $c->addCond('OCR_' . $ocr->ocr_meta_desc, '==', $_POST['OCR_'][$i]);
                            if ($groupBy == 'carat') {
                                $condition = new Condition('OCR_' . $ocr->ocr_meta_desc, '==', $_POST['OCR_'][$i]);
                                array_push($conditions, $condition);
                            }
                        }
                    }
                    $i++;
                }
                $c->limit(Idc::PAGE_SIZE);
                $c->offset(($currentPage - 1) * Idc::PAGE_SIZE);
                $content = $content . $this->getDocsByType($c, $docType, $conditions, $docsLevel, $groupBy, $fields);
            }
            echo $content;
        }
    }

    /**
     * Busqueda inicial por tipo de documento. Devuelve las caratulas.
     *
     */
    private function getDocsByType($c, $carats, $conditions, $docsLevel = 'c1', $groupBy = 'carat', $fields = null) {
        $currentPage = ($c->getOffset() == 0) ? 1 : (($c->getOffset() / Idc::PAGE_SIZE) + 1);
        $getGroup = new GetGroupController();
        $group = $getGroup->getGroup($c, $carats, $conditions, $docsLevel, $groupBy, $fields);
        $getCResult = new GetContentResultController();
        return $getCResult->getContentResult($group, $currentPage, $docsLevel, $fields, '/getResults/results', $groupBy);
    }

}
