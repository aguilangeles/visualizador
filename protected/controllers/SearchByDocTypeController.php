<?php

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

    private function getDocsByType($c, $carats, $conditions, $docsLevel = 'c1', $groupBy = 'carat', $fields = null) {
        $currentPage = ($c->getOffset() == 0) ? 1 : (($c->getOffset() / Idc::PAGE_SIZE) + 1);
        $group = $this->getGroup($c, $carats, $conditions, $docsLevel, $groupBy, $fields);
        return $this->getContentResult($group, $currentPage, $docsLevel, $fields, '/getResults/results', $groupBy);
    }

    protected function getGroup($criteria, $doctype, $conditions, $docsLevel = 'c1', $groupBy = 'carat', $fields = null) {
        $keys = array('docType');
        if ($groupBy == 'carat') {
            $keys = array($docsLevel, 'docType');
        }
        foreach ($fields as $field) {
            array_push($keys, $field->prefix . $field->name);
        }
        $c = '';
        $reduce5 = '';
        $carats = DocTypes::model()->getMetaCarats($doctype->doc_type_id);
        foreach ($carats as $carat) {
            $reduce5 = $reduce5 . 'prev.images.push(obj.' . 'CMETA_' . $carat . ');';
            $c = $c . $carat . ",";
        }
        $reduce5 = 'prev.images.push("' . $c . '");' . $reduce5;
        $keys = array_flip($keys);
        $initial = array("images" => array(), "index" => 0, "info" => array());
        $reduce1 = "function (obj, prev) { ";
        $reduce2 = 'prev.images.push(obj._id);';
        $reduce3 = 'prev.info.push(obj.order);prev.images.push(obj.docType);prev.images.push(obj.docSubtipo);prev.images.push(obj.visibleCarat);prev.images.push(obj.order);prev.images.push(obj.visibleImagen);prev.images.push(obj.fileName);prev.images.push(obj.filePath);prev.images.push(obj.face);prev.images.push(obj.idPapel);prev.images.push(obj.IDC);';
        $reduce4 = '';
        $o = "";
        $ocrs = DocTypes::model()->getMetaOcrs($doctype->doc_type_id);
        if ($ocrs != null) {
            foreach ($ocrs as $ocr) {
                $reduce4 = $reduce4 . 'prev.images.push(obj.' . 'OCR_' . $ocr . ');';
                $o = $o . $ocr . ",";
            }
        }

        $reduce4 = 'prev.images.push("' . $o . '");' . $reduce4;
        $reduce6 = 'prev.index += 1;}';
        $reduce = $reduce1 . $reduce2 . $reduce3 . $reduce5 . $reduce4 . $reduce6;
        $group = Idc::model()->group($keys, $initial, $reduce, $criteria);
        $result = array('keys' => $conditions, 'data' => $group);

        return $result;
    }

    private function getContentResult($result, $currentPage, $docsLevel, $fields, $view = 'results', $groupBy = 'carat') {
        if ($result['data']['ok']) {
            if ($result['data']['count'] > 10000) {
                $content = '<div class="errorMessage">Se encontraron mas de 10.000 coincidencias, Por favor, refine aún mas su busqueda.</div>';
            } else {
                if ($result['data']['count'] == 0) {
                    $content = '<div class="errorMessage"><img src="../images/error.png" style="height:25px;margin-bottom:-6px;"> No se encontraron resultados.</div>';
                } else {
                    if ($result['data'] != null) {
                        $finded = (int) $result['data']["keys"];
                        if ($finded == 0) {
                            $content = '<div class="errorMessage"><img src="../images/error.png" style="height:25px;margin-bottom:-6px;"> No se encontraron resultados.</div>';
                        } else {
                            $content = '<div class="okMessage"><img src="../images/ok.png" style="height:25px;margin-bottom:-6px;">Se encontraron ' . $finded . ' resultados</div>';
                            $pages = ceil($finded / Idc::PAGE_SIZE);
                            $content = $content . $this->renderPartial($view, array('pages' => $pages, 'currentPage' => $currentPage, 'group' => $result, 'docsLevel' => $docsLevel, 'fields' => $fields, 'groupBy' => $groupBy), true, false);
                        }
                    } else {
                        $content = '<div class="errorMessage"><img src="../images/error.png" style="height:25px;margin-bottom:-6px;">  Por favor, filtre aún mas su busqueda.</div>';
                    }
                }
            }
        } else {
            if ($result["data"]["code"] == 10334) {
                $content = '<div class="errorMessage">Se encontraron mas de 10.000 coincidencias, Por favor, refine aún mas su busqueda.</div>';
            } else {
                $content = '<div class="errorMessage"><img src="../images/error.png" style="height:25px;margin-bottom:-6px;">  ' . $result["data"]["errmsg"] . '.</div>';
            }
        }
        return $content;
    }

}
