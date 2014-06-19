<?php

class SiteController extends Controller {

    /**
     * Constante que define un layout vacío.
     */
    const EMPTY_LAYOUT = 'empty_layout';

    /**
     * Directorio donde se crearan las imágenes para ser usadas por Open Zoom.
     */
    const OZ_DIRECTORY = 'images/temp/oz/';

    /**
     * Identificador en mongo, de una imagen Open Zoom.
     */
    const OZ_DOCSUBTYPE = 'OZ';

    /**
     * Ruta de la imagen a visualizar. Inicializada en null.
     * @var string
     * @author GDM
     */
    private $filePath = null;

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
          // captcha action renders the CAPTCHA image displayed on the contact page
          'captcha' => array(
            'class' => 'CCaptchaAction',
            'backColor' => 0xFFFFFF,
          ),
          // page action renders "static" pages stored under 'protected/views/site/pages'
          // They can be accessed via: index.php?r=site/page&view=FileName
          'page' => array(
            'class' => 'CViewAction',
          ),
        );
    }

    public function actionInfo() {
        $this->render('info');
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {

        if (Yii::app()->user->isGuest) {//se fija si esta autenticado
            $this->redirect('/site/login');
        } else {
            $user = Users::model()->findByPk((int) Yii::app()->user->id);
            $docLevel1 = array('0' => '[SELECCIONE UN TIPO DE DOC]');
            $docLevel2 = array('0' => '[SELECCIONE UN TIPO DE DOC]');
            $docLevel3 = array('0' => '[SELECCIONE UN TIPO DE DOC]');
            $docLevel4 = array('0' => '[SELECCIONE UN TIPO DE DOC]');
            $rotulos = array('0' => '[SELECCIONE UN RÓTULO]');
            foreach ($user->GroupsAsoc as $group) {
                foreach ($group->Group->DoctypesAsoc as $docType) {
                    $documento = $docType->DocumentType->doc_type_desc;
                    switch ($docType->DocumentType->doc_type_level) {
                        case 1: $docLevel1 = $docLevel1 + array($docType->DocumentType->doc_type_id => $docType->DocumentType->doc_type_label);
                            break;
                        case 2: $docLevel2 = $docLevel2 + array($docType->DocumentType->doc_type_id => $docType->DocumentType->doc_type_label);
                            break;
                        case 3: $docLevel3 = $docLevel3 + array($docType->DocumentType->doc_type_id => $docType->DocumentType->doc_type_label);
                            break;
                        case 4: $docLevel4 = $docLevel4 + array($docType->DocumentType->doc_type_id => $docType->DocumentType->doc_type_label);
                            break;
                    }
                }
            }
            $rots = Rotulos::model()->findAll();
            foreach ($rots as $rot) {
                if (Users::model()->getRotulosPermission($rot->DocsIds)) {
                    foreach ($rot->DocsIds as $docId) {
                        $rotulos = $rotulos + array($rot->rotulo_id => $rot->rotulo_desc);
                    }
                }
            }
            $doc = $user->getGroups();
            $model = new Idc();

            $this->render('index', array('model' => $model,
              'docLevel1' => $docLevel1,
              'docLevel2' => $docLevel2,
              'docLevel3' => $docLevel3,
              'docLevel4' => $docLevel4,
              'rotulos' => $rotulos,
            ));
        }
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        $error = '';
        if ($error == Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    public function actionSearchRotulos() {
        if (isset($_POST['rotulo_id'])) {
            $content = "";
            $rotuloId = (int) $_POST['rotulo_id'];
            $rotulo = Rotulos::model()->findByPk($rotuloId);
            $docId = $rotulo->Docs[0]->doc_type_id;
            $doc = DocTypes::model()->findByPk($docId);
            foreach ($doc->Carats as $carat) {
                $content = $content . '<div class="level-tag" style="width:300px">' . $carat->carat_meta_label . '</div>';
                $content = $content . '<div class="level-tag" style="width:300px">' . CHtml::textField('CMETA_[]') . '</div>';
            }
            echo $content;
        }
    }

    public function actionLogin() {
        $model = new LoginForm;
        $this->layout = 'login';
        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        $this->render('login', array('model' => $model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    
    private function getCaratMeta($docs) {
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

    /**
     * Búsqueda por tipo de documento.
     * @param int $currentPage
     * @return string
     * @author GDM
     */
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
//                                $condition = new Condition('docType','or',$document->doc_type_desc);
//                                array_push($conditions, $condition);
            }
            $carats = $this->getCaratMeta(array($doc->doc_type_id => $doc->doc_type_id));
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

    public function actionSearchGeneral() {
        $result = "";
        $docsLevel1 = Users::getAllDocTypes((int) Yii::app()->user->id, 1);
        echo CHtml::checkBoxList('DocRest', array_keys($docsLevel1), $docsLevel1, array('labelOptions' => array('style' => 'text-align:left;width:100px;')));
    }

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

    /**
     * Devuelve el encabezado de la búsqueda.
     * @param type $group
     * @param type $currentPage
     * @param type $docsLevel
     * @param type $fields
     * @return string 
     */
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

    /**
     * Busqueda inicial por tipo de documento. Devuelve las caratulas.
     *
     */
    private function getDocsByType($c, $carats, $conditions, $docsLevel = 'c1', $groupBy = 'carat', $fields = null) {
        $currentPage = ($c->getOffset() == 0) ? 1 : (($c->getOffset() / Idc::PAGE_SIZE) + 1);
        $group = $this->getGroup($c, $carats, $conditions, $docsLevel, $groupBy, $fields);
        return $this->getContentResult($group, $currentPage, $docsLevel, $fields, 'results', $groupBy);
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
            $group = $this->getGroup($c, $docType, $ocrs, $docType->doc_type_level, 'carat', $fields);
            $content = $content . $this->getContentResult($group, $currentPage, $docType->doc_type_level, $fields, 'results_general');
        }
        return $content;
    }

    /**
     * Busqueda inicial por rótulo. Devuelve las caratulas.
     *
     */
    private function getDocsByRotulo($c, $carats, $ocrs, $fields, $docsLevel = 'c1') {
        $currentPage = ($c->getOffset() == 0) ? 1 : (($c->getOffset() / Idc::PAGE_SIZE) + 1);
        $group = $this->getGroup($c, $carats, $ocrs, $docsLevel, 'carat', $fields);
        return $this->getContentResult($group, $currentPage, $docsLevel, $fields, 'results_rotulos');
    }

    public function actionGetImagesById() {
        if (isset($_POST["items"])) {

            $query = json_decode($_POST["query"]);
            $conditions = Condition::setConditions($query);
            $conditions->sort('idPapel', EMongoCriteria::SORT_ASC);
            $model = Idc::model()->findAll($conditions);
            $showOrder = Condition::noImageMetaData($query);
            if (!Idc::isOrdered($model)) {
                Idc::Sort($query);
            }

//                      foreach ($model as $item)
//                      {
//                          $caja = $item->OCR_DISTRITO;
//                      }
            $infoId = $_POST["infoId"];
            $imageData = json_decode($_POST["items"]);
            //Agregado para obtener el subtipo de documento //Gonza
            $tipo = $imageData[2];
            //var_dump($imageData);die();
            $items = Image::getImages($infoId, $model);
            $items2 = Image::getImages2($infoId, $model);
            Yii::app()->getSession()->add('totalImagenes', $items2);

            //$items = Image::getImages($infoId, $imageData);
            $imageData = str_replace(array('\\', '"'), array('|', '\"'), $imageData);
            $content = "<div id='imageList" . $items['id'] . "' style='display:none'>" . json_encode($items) . "</div>";
            $content = $content . "<div id='imageList2" . $items2['id'] . "' style='display:none'>" . json_encode($items2) . "</div>";
            $content = $content . '<div style="height:200px;position:relative;overflow:auto;">';
            $content = $content . '<table id="' . $items['id'] . '" style="table-layout: fixed; word-wrap:break-word;"><thead><tr>';
            if (Yii::app()->user->isAdmin) {
                $content = $content . '<th scope="col">Visible</th>';
            }
            $content .= ($showOrder) ? '<th scope="col">Orden</th>' : '';
            //$content = $content.'<th scope="col">Orden</th>';
            $content = $content . '<th scope="col" style="width: 65px;">Acciones</th>';
            if ($items['images'][0]->oMeta != null) {
                foreach ($items['images'][0]->oMeta as $campo) {
                    $content = $content . '<th scope="col">' . key($campo) . '</th>';
                }
            }
            $content = $content . '</tr></thead><tbody>';
            for ($x = 0; $x < count($items['images']); $x++) {
                $content = $content . '<tr>';
                if (Yii::app()->user->isAdmin) {
                    $levels = array();

                    $levels[] = $items['images'][$x]->softAttributes['c1'];
                    $levels[] = $items['images'][$x]->softAttributes['c2'];
                    $levels[] = $items['images'][$x]->softAttributes['c3'];
                    $levels[] = $items['images'][$x]->softAttributes['c4'];

                    $content = $content . '<td>' . CHtml::checkBox("check_" . $items['id'] . "_" . $x, $items['images'][$x]->visibleImagen, array('onClick' => 'js:toogleImageVisibility("' . $items['id'] . '","' . $x . '")', 'class' => 'check_' . $items['id'])) . '</td>';
                    $content .= ($showOrder) ? '<td>' . CHtml::textField("orden_" . $items['images'][$x]->id, $items['images'][$x]->idPapel, array('style' => 'width:30px;padding: 0;margin: 0;', 'onChange' =>
                          'js:setOrder("' . $items['images'][$x]->id . '","' . $items['images'][$x]->idPapel . '","' . $levels[0] . '","' . $levels[1] . '","' . $levels[2] . '","' . $levels[3] . '")')) . '</td>' : '';
                } else {
                    $content = $content . '<td>' . $items['images'][$x]->idPapel . '</td>';
                }
                //Agregado para tomar los OZ con zoom y los demas reducidos // Gonza				
                if ($tipo == 'OZ') {
                    $content = $content . '<td>' . CHtml::link(CHtml::image('/images/image.png'), '#', array('style' => 'margin-right:5px;', 'onClick' => 'showImage("' . $items['id'] . '","' . $x . '"); return false;',));
                } else {
                    $content = $content . '<td>' . CHtml::link(CHtml::image('/images/image.png'), '#', array('style' => 'margin-right:5px;', 'onClick' => 'showImageSmall("' . $items['id'] . '","' . $x . '"); return false;',));
                }

                $edit_image = (Yii::app()->user->isAdmin) ? CHtml::link(CHtml::image('/images/edit_icon.png'), '#', array('onClick' => 'openMetaForm("' . $items['images'][$x]->id . '"); return false;',)) : '';
                $content .= $edit_image . '</td>';
                for ($i = 0; $i < count($items['images'][$x]->oMeta); $i++) {
                    $ocr_field_name = OcrMeta::getOCRNameByLabel(key($items['images'][$x]->oMeta[$i]));
                    $field_name = 'OCR_' . strtoupper($ocr_field_name) . '_';
                    $content = $content . '<td id="' . $field_name . $items['images'][$x]->id . '">' . current($items['images'][$x]->oMeta[$i]) . '</td>';
                }
                $content = $content . '</tr>';
            }
            $content = $content . '</tbody></table>';
        }
        $button = '';
        if ($items['hasMore']) {
            $button = "<button id='seeMore" . $items['id'] . "' style='margin:20px;' type='submit' name='searchMore' onClick='seeMore(\"" . $items['id'] . "\");'>Ver Mas</button>";
        }
        $html = $content . $button . '</div>';
        $response = array('html' => $html, 'imageData' => json_encode($model->serialize()));
        echo CJSON::encode($response);
    }

    /**
     * Cast an object to another class, keeping the properties, but changing the methods
     *
     * @param string $class  Class name
     * @param object $object
     * @return object
     */
    function casttoclass($class, $object) {
        return unserialize(preg_replace('/^O:\d+:"[^"]++"/', 'O:' . strlen($class) . ':"' . $class . '"', serialize($object)));
    }

    public function actionSeeMore() {
        $limit = 50;
        $imageList = get_object_vars(json_decode($_POST["imageList"]));
        $start = $imageList['qty'];
        $query = json_decode($_POST["query"]);
        $showOrder = Condition::noImageMetaData($query);
        $conditions = Condition::setConditions($query);
        $conditions->sort('idPapel', EMongoCriteria::SORT_ASC);
        $imageData = Idc::model()->findAll($conditions);
        $id = $imageList['id'];
        $items = Image::getImages($id, $imageData, $imageList);
        $content = '';
        for ($x = $start; $x < count($items['images']); $x++) {
            $content = $content . '<tr>';
            if (Yii::app()->user->isAdmin) {
                $levels = array();

                $levels[] = $items['images'][$x]->softAttributes['c1'];
                $levels[] = $items['images'][$x]->softAttributes['c2'];
                $levels[] = $items['images'][$x]->softAttributes['c3'];
                $levels[] = $items['images'][$x]->softAttributes['c4'];

                $content = $content . '<td>' . CHtml::checkBox("check_" . $items['id'] . "_" . $x, $items['images'][$x]->visibleImagen, array('onClick' => 'js:toogleImageVisibility("' . $items['id'] . '","' . $x . '")', 'class' => 'check_' . $items['id'])) . '</td>';
                $content .= ($showOrder) ? '<td>' . CHtml::textField("orden_" . $items['images'][$x]->id, $items['images'][$x]->idPapel, array('style' => 'width:30px;padding: 0;margin: 0;', 'onChange' =>
                      'js:setOrder("' . $items['images'][$x]->id . '","' . $items['images'][$x]->idPapel . '","' . $levels[0] . '","' . $levels[1] . '","' . $levels[2] . '","' . $levels[3] . '")')) . '</td>' : '';
            } else {
                $content = $content . '<td>' . $items['images'][$x]->idPapel . '</td>';
            }
            $filePath = $items['images'][$x]->filePath . '|Imagenes|' . $items['images'][$x]->fileName;
            $content = $content . '<td>' . CHtml::link(CHtml::image('/images/image.png'), '#', array('style' => 'margin-right:5px;', 'onClick' => 'showImage("' . $items['id'] . '","' . $x . '"); return false;',));
            $edit_image = (Yii::app()->user->isAdmin) ? CHtml::link(CHtml::image('/images/edit_icon.png'), '#', array('onClick' => 'openMetaForm("' . $items['images'][$x]->id . '"); return false;',)) : '';
            $content .= $edit_image . '</td>';
            for ($i = 0; $i < count($items['images'][$x]->oMeta); $i++) {
                $content = $content . '<td>' . current($items['images'][$x]->oMeta[$i]) . '</td>';
            }
            $content = $content . '</tr>';
        }
        $response = array('id' => $items['id'], 'table' => $content, 'imageList' => CJSON::encode($items), 'hasMore' => $items['hasMore']);
        echo CJSON::encode($response);
    }

    /**
     * Acción muestra imagen. 
     */
    public function actionViewImage() {

        $this->layout = self::EMPTY_LAYOUT;
        $widthSize = ((int) $_POST['widthsize'] - 40);
        $rotar = ((int) $_POST['rotar']);
        //$imageList = get_object_vars(json_decode($_POST['imageList']));
        $imageList = get_object_vars(json_decode($_POST['imageList2']));
//            $grado = get_object_vars(json_decode($_POST['grado']));
//                    
//            $gestor = fopen('filename.txt', 'w');
//                        $dump = var_export($grado, true);
//                        $fw = fwrite($gestor, $dump);
//                        fclose($gestor);
        //die(var_dump($imageList));
        $currIndex = (int) $_POST['currIndex'];
        $currSubIndex = $_POST['currSubIndex'];
        if ($currSubIndex != 'undefined') {
            $imageFather = $imageList['images'][$currIndex];
            $image = $imageList['images'][$currIndex]->reverseImage[$currSubIndex];
        } else {
            $image = $imageList['images'][$currIndex];
        }

        $cantidadImagenes = count($imageList['images']);
        $this->filePath = $image->filePath . "|Imagenes|" . $image->fileName;
        $hasPrevArrow = ($image->prevImageIndex == $image->currImageIndex) ? 'visibility:hidden;' : '';
        $hasNextArrow = ($image->nextImageIndex == $image->currImageIndex) ? 'visibility:hidden;' : '';
        $NextubImages = '';
        $PrevSubImages = '';
        if ($image->face == 'Anverso') {
            $NextubImages = $this->getNextSubImage($image, $imageList['id']);
        } else {
            $PrevSubImages = $this->getPrevSubImage($image, $imageFather, $imageList['id']);
        }
        if ($image->docSubtipo == self::OZ_DOCSUBTYPE) {
            $divVisible = 'visibility:hidden;';
        } else {
            $divVisible = 'visibility:visible;';
        }
        $toolBar = '<div id="image-toolbar" style="float:left;width: 100%;">';
        $toolBar = $toolBar . '<div style="float:left;' . $hasPrevArrow . '">' . CHtml::link(CHtml::image("/images/Arrow-Right.png", "#", array('onclick' => 'showImageSmall("' . $imageList['id'] . '",' . $image->prevImageIndex . ');')), "", array('id' => 'prevImage', 'style' => 'cursor:pointer;')) . '</div>';
        $toolBar = $toolBar . '<div style="float:left;margin-left: 43%;' . $divVisible . '">' . CHtml::link(CHtml::image("/images/zoomMenos.png", "#", array('onclick' => 'showImageSmall("' . $imageList['id'] . '",' . $image->currImageIndex . ');')), "", array('id' => 'prevImage', 'style' => 'cursor:pointer;')) . CHtml::link(CHtml::image("/images/zoomMas.png", "#", array('onclick' => 'showImage("' . $imageList['id'] . '",' . $image->currImageIndex . ');')), "", array('id' => 'prevImage', 'style' => 'cursor:pointer;')) . '</div>';

        $toolBar = $toolBar . $PrevSubImages;

        $toolBar = $toolBar . '<div style="float:right;' . $hasNextArrow . '">' . CHtml::link(CHtml::image("/images/Arrow-Left.png"), "", array('id' => 'nextImage', 'style' => 'cursor:pointer;', 'onclick' => 'showImageSmall("' . $imageList['id'] . '",' . $image->nextImageIndex . ')')) . '</div>';

        $toolBar = $toolBar . $NextubImages;
        $toolBar = $toolBar . '</div>';
        $toolBar = $toolBar . '<div id="info" style="float:left;width: 100%;text-align: center;">Tipo de Documeto: <b>' . $image->docType . '</b> | Subtipo de Documento: <b>' . $image->docSubtipo . '</b></div>';
        $toolBar = $toolBar . '<div id="image-cmeta" style="float:left;width: 100%;text-align: center;">' . Image::getImageCaratMeta($image) . '</div>';
        $toolBar = $toolBar . '<div id="image-meta" style="float:left;width: 100%;text-align: center;">' . Image::getImageOcrMeta($image) . '</div>';
        if ($image->docSubtipo == self::OZ_DOCSUBTYPE) {
            $destination = $this->getDestination($image->IDC);
            $url = '<div>' . $this->renderPartial('//showimage/oz', array('destination' => '../' . $destination, 'widthSize' => $widthSize,), TRUE, TRUE) . '</div>';
            $toolBar = $toolBar . $url;
        } else {
            //$toolBar = $toolBar.'<div style="float:left;'.$hasPrevArrow.'">'.CHtml::link(CHtml::image("/images/Arrow-Right.png","#",array('onclick'=>'showImageSmall("'.  $imageList['id'].'",'.$image->prevImageIndex.');')),"",array('id'=>'prevImage','style'=>'cursor:pointer;')).'</div>';
            $toolBar = $toolBar . '<div id="image-meta" style="float:left;width: 100%;text-align: center;">' . CHtml::link(CHtml::image("/images/rotar.png"), "", array('id' => 'nextImage', 'style' => 'cursor:pointer;', 'onclick' => 'rotarImagen("' . $imageList['id'] . '",' . $image->currImageIndex . ')')) . '</div>';
            //$toolBar = $toolBar.'<div id="image-meta" style="float:left;width: 100%;text-align: center;">'.CHtml::link(CHtml::image('/images/rotar.png'),'#', array('id'=>'rotate_button','onClick'=>'rotarImagen(""); return false;',));
            $url = $this->createUrl('showimage/view/', array('widthSize' => $widthSize, 'path' => $this->getValidFile(), 'doc' => $image->docType, 'subdoc' => $image->docSubtipo));
            //$toolBar = $toolBar.CHtml::image($url); 
            $toolBar = $toolBar . '<div style="width: 100%;height:100%;float:left;overflow:hidden;"><div style="overflow-x:visible;overflow-y:hidden;position:relative;width:98%;height:98%;float:left;"><img id="imgprincipal" style="-webkit-transform:rotate(' . $rotar . 'deg);" src="' . $url . '"></div></div>';
        }
        $toolBar = $toolBar . '<div id="image-toolbar-footer" style="float:left;width: 100%;">';
        $toolBar = $toolBar . '<div style="float:left;' . $hasPrevArrow . '">' . CHtml::link(CHtml::image("/images/Arrow-Right.png", "#", array('onclick' => 'showImageSmall("' . $imageList['id'] . '",' . $image->prevImageIndex . ');')), "", array('id' => 'prevImage', 'style' => 'cursor:pointer;')) . '</div>';
        $toolBar = $toolBar . $PrevSubImages;
        $toolBar = $toolBar . '<div style="float:right;' . $hasNextArrow . '">' . CHtml::link(CHtml::image("/images/Arrow-Left.png"), "", array('id' => 'nextImage', 'style' => 'cursor:pointer;', 'onclick' => 'showImageSmall("' . $imageList['id'] . '",' . $image->nextImageIndex . ')')) . '</div>';
        $toolBar = $toolBar . $NextubImages;
        $toolBar = $toolBar . '</div>';
        echo $toolBar;
    }

    private function getPrevSubImage($image, $imageFather, $imageListId) {
        $prevImage = $image->currSubIndex - 1;
        if ($prevImage == -1) {
            return '<div style="float:left;padding-left: 50px;">' . CHtml::link(CHtml::image("/images/prev_img.png"), "", array('id' => 'prevSubImage', 'style' => 'cursor:pointer;', 'onclick' => 'showImageSmall("' . $imageListId . '",' . $imageFather->currImageIndex . ')')) . '</div>';
        } else {
            return '<div style="float:left;padding-left: 50px;">' . CHtml::link(CHtml::image("/images/prev_img.png"), "", array('id' => 'prevSubImage', 'style' => 'cursor:pointer;', 'onclick' => 'showImageSmall("' . $imageListId . '",' . $imageFather->currImageIndex . ',' . $prevImage . ')')) . '</div>';
        }
    }

    private function getNextSubImage($image, $imageListId) {
        $html = '';
        $hasSubImages = (count($image->reverseImage) > 0);
        if ($hasSubImages) {
            $html = '<div style="float:right;padding-right: 50px;">' . CHtml::link(CHtml::image("/images/next_img.png"), "", array('id' => 'nextSubImage', 'style' => 'cursor:pointer;', 'onclick' => 'showImageSmall("' . $imageListId . '",' . $image->currImageIndex . ',' . $image->reverseImage[0]->currSubIndex . ')')) . '</div>';
        }
        return $html;
    }

    public function actionToogleCaratVisibility() {
        $result = array('message', 'data');
        $docType = null;
        $action = $_POST["action"];
        $conditions = json_decode($_POST["query"]);
        $fields = json_decode($_POST["fields"]);
        $c = Condition::setConditions($conditions);
        foreach ($fields as $field) {
            $docType = DocTypes::getDocumentByDocTypeDesc($field->doc);
            break;
        }
        $modifier = new EMongoModifier();
        if ($action == "show") {
            $modifier->addModifier('visibleCarat', 'set', TRUE);
            $modifier->addModifier('visibleImagen', 'set', TRUE);
            if (Idc::model()->updateAll($modifier, $c)) {
                $result['message'] = "Se muestran exitosamente todos los documentos.";
            }
        } else {
            $modifier->addModifier('visibleCarat', 'set', FALSE);
            $modifier->addModifier('visibleImagen', 'set', FALSE);
            if (Idc::model()->updateAll($modifier, $c)) {
                $result['message'] = "Se ocultaron exitosamente todos los documentos.";
            }
        }
        $group = $this->getGroup($c, $docType, $conditions, null, 'images', $fields);
        $result['image'] = json_encode($group['data']['retval'][0]['images']);
        echo CJSON::encode($result);
    }

    public function actionToogleImageVisibility() {
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

    private function getIds($levels, $oldPos) {
        $result = array();
        $criteria = new EMongoCriteria();
        foreach ($levels as $level) {
            if (!empty($level[1])) {
                $criteria->addCond($level[0], '==', $level[1]);
            }
        }
        $criteria->addCond('idPapel', '==', $oldPos);
        $records = Idc::model()->findAll($criteria);
        foreach ($records as $record) {
            array_push($result, $record->_id);
        }
        return $result;
    }

    public function actionSetOrder() {
        $oldPos = (int) $_POST['oldPos'];
        $newPos = (int) $_POST['newPos'];
        $id = $_POST['id'];

        $levels = array();
        $levels[] = array('c1', isset($_POST['c1']) ? $_POST['c1'] : '');
        $levels[] = array('c2', isset($_POST['c2']) ? $_POST['c2'] : '');
        $levels[] = array('c3', isset($_POST['c3']) ? $_POST['c3'] : '');
        $levels[] = array('c4', isset($_POST['c4']) ? $_POST['c4'] : '');

        $criteria = new EMongoCriteria();

        foreach ($levels as $level) {
            if (!empty($level[1])) {
                $criteria->addCond($level[0], '==', $level[1]);
            }
        }

        $ids = $this->getIds($levels, $oldPos);

        $criteria->sort('idPapel', EMongoCriteria::SORT_DESC);
        $records = Idc::model()->findAll($criteria);

        $qty = count($records);

        if ($newPos > $qty) {
            echo "Error, el valor no puede ser mas grande que " . $qty;
        } else if ($newPos < 1) {
            echo "Error, el valor no puede ser menor que 0";
        } else {
            $criteria->setSort(array('idPapel' => EMongoCriteria::SORT_ASC));
            if ($oldPos > $newPos) {//se mueve para abajo
                $criteria->addCond('idPapel', '<', $oldPos);
                $criteria->addCond('idPapel', '>=', $newPos);
                $modifier = new EMongoModifier();
                $modifier->addModifier('idPapel', 'inc', 1);
                $status = Idc::model()->updateAll($modifier, $criteria);
                $criteria = new EMongoCriteria();
                $modifier = new EMongoModifier();
                foreach ($ids as $id) {
                    $criteria->addCond('_id', '==', new MongoID($id));
                    $modifier->addModifier('idPapel', 'set', $newPos);
                    $status = Idc::model()->updateAll($modifier, $criteria);
                }
            } else {
                $criteria->addCond('idPapel', '>', $oldPos);
                $criteria->addCond('idPapel', '<=', $newPos);
                //se les resta uno
                $modifier = new EMongoModifier();
                $modifier->addModifier('idPapel', 'inc', -1);
                $status = Idc::model()->updateAll($modifier, $criteria);
                $criteria = new EMongoCriteria();
                $modifier = new EMongoModifier();
                foreach ($ids as $id) {
                    $criteria->addCond('_id', '==', new MongoID($id));
                    $modifier->addModifier('idPapel', 'set', $newPos);
                    $status = Idc::model()->updateAll($modifier, $criteria);
                }
            }
        }
    }

    protected function orderResults($group, $qty = 1) {
        $result = array();
        $r = $group['retval'][0]['images'];
        $p = $group['retval'][0]['info'];
        $i = 0;
        foreach ($p as $index) {
            $start = (($index - 1) * $qty);
            $end = $start + $qty;
            for ($j = $start; $j < $end; $j++) {
                $result = $result + array($j => $r[$i]);
                $i++;
            }
        }
        ksort($result);
        $group['retval'][0]['images'] = $result;
        return $group;
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

    function output_file($file, $name, $mime_type = '') {
        /*
          This function takes a path to a file to output ($file),
          the filename that the browser will see ($name) and
          the MIME type of the file ($mime_type, optional).

          If you want to do something on download abort/finish,
          register_shutdown_function('function_name');
         */
        if (!is_readable($file))
            die('File not found or inaccessible!');

        $size = filesize($file);
        $name = rawurldecode($name);

        /* Figure out the MIME type (if not specified) */
        $known_mime_types = array(
          "pdf" => "application/pdf",
          "txt" => "text/plain",
          "html" => "text/html",
          "htm" => "text/html",
          "exe" => "application/octet-stream",
          "zip" => "application/zip",
          "doc" => "application/msword",
          "xls" => "application/vnd.ms-excel",
          "ppt" => "application/vnd.ms-powerpoint",
          "gif" => "image/gif",
          "png" => "image/png",
          "jpeg" => "image/jpg",
          "jpg" => "image/jpg",
          "php" => "text/plain"
        );

        if ($mime_type == '') {
            $file_extension = strtolower(substr(strrchr($file, "."), 1));
            if (array_key_exists($file_extension, $known_mime_types)) {
                $mime_type = $known_mime_types[$file_extension];
            } else {
                $mime_type = "application/force-download";
            };
        };

        @ob_end_clean(); //turn off output buffering to decrease cpu usage
        // required for IE, otherwise Content-Disposition may be ignored
        if (ini_get('zlib.output_compression'))
            ini_set('zlib.output_compression', 'Off');

        header('Content-Type: ' . $mime_type);
        header('Content-Disposition: attachment; filename="' . $name . '"');
        header("Content-Transfer-Encoding: binary");
        header('Accept-Ranges: bytes');

        /* The three lines below basically make the 
          download non-cacheable */
        header("Cache-control: private");
        header('Pragma: private');

        // multipart-download and download resuming support
        if (isset($_SERVER['HTTP_RANGE'])) {
            list($a, $range) = explode("=", $_SERVER['HTTP_RANGE'], 2);
            list($range) = explode(",", $range, 2);
            list($range, $range_end) = explode("-", $range);
            $range = intval($range);
            if (!$range_end) {
                $range_end = $size - 1;
            } else {
                $range_end = intval($range_end);
            }

            $new_length = $range_end - $range + 1;
            header("HTTP/1.1 206 Partial Content");
            header("Content-Length: $new_length");
            header("Content-Range: bytes $range-$range_end/$size");
        } else {
            $new_length = $size;
            header("Content-Length: " . $size);
        }

        /* output the file itself */
        $chunksize = 1 * (1024 * 1024); //you may want to change this
        $bytes_send = 0;
        if ($file = fopen($file, 'r')) {
            if (isset($_SERVER['HTTP_RANGE']))
                fseek($file, $range);

            while (!feof($file) &&
            (!connection_aborted()) &&
            ($bytes_send < $new_length)
            ) {
                $buffer = fread($file, $chunksize);
                print($buffer); //echo($buffer); // is also possible
                flush();
                $bytes_send += strlen($buffer);
            }
            fclose($file);
            unlink($file);
        } else
            die('Error - can not open file.');

        die();
    }

    public function actionGetZip() {
        $fileName = $_GET['fileName'];
        $this->output_file($fileName, uniqid() . '.zip', 'zip');
    }

    public function actionRemoveTempZip() {
        $filename = Yii::app()->session['tempFileName'];
        unset(Yii::app()->session['tempFileName']);
        unlink($filename);
    }

    public function actionExportZip() {
        $this->layout = self::EMPTY_LAYOUT;
        $conditions = json_decode($_POST["conditions"]);
        $c = Condition::setConditions($conditions);
        $c->select(array('docType', 'fileName', 'filePath'));
        $images = Idc::model()->findAll($c);
        $imageList = array();
        foreach ($images as $image) {
            array_push($imageList, array('docType' => $image->docType, 'path' => $image->filePath . '|Imagenes|', 'file' => $image->fileName));
        }
        $zip = new EZip();
        foreach ($imageList as $image) {
            $pathW = str_replace('|', '\\', $image['path'] . $image['file']);
            $pathL = str_replace('|', '/', $image['path'] . $image['file']);
            if (file_exists($pathW)) {
                $im = $this->setWaterMark($pathW, $image['docType']);
                $zip->add_fileFromString($im->getimageblob(), $image['file']);
            } else if (file_exists($pathL)) {
                $im = $this->setWaterMark($pathL, $image['docType']);
                $zip->add_fileFromString($im->getimageblob(), $image['file']);
            }
        }
        $fileContent = $zip->file();
        $fileName = tempnam('images/temp/', 'zip') . '.zip';
        $fhandle = fopen($fileName, 'w');
        fwrite($fhandle, $zip->file());
        fclose($fhandle);
        echo $fileName;
    }

    public function actionExportPDF() {
        /* echo 'arranca la generacion del pdf'; */
        ini_set('memory_limit', '800M');
        set_time_limit(800);
        $this->layout = self::EMPTY_LAYOUT;
        $conditions = json_decode($_POST["conditions"]);
        $c = Condition::setConditions($conditions);
        $c->select(array('docType', 'fileName', 'filePath'));
        $images = Idc::model()->findAll($c);
        $imageList = array();
        foreach ($images as $image) {
            array_push($imageList, array('docType' => $image->docType, 'path' => $image->filePath . '|Imagenes|', 'file' => $image->fileName));
        }
        $pdf = new FPDF('P', 'mm');
        $pdf->SetFont('Arial', 'B', 16);

        foreach ($imageList as $image) {
            $pathW = str_replace('|', '\\', $image['path'] . $image['file']);
            $pathL = str_replace('|', '/', $image['path'] . $image['file']);
            if (file_exists($pathW)) {
                $im = $this->setWaterMark($pathW, $image['docType']);
            } else if (file_exists($pathL)) {
                $im = $this->setWaterMark($pathL, $image['docType']);
            }
            if ($im != null) {
                $widht = $im->getImageWidth();
                $height = $im->getImageHeight();
                $resolution = $im->getimageresolution();
                $orientation = ($widht > $height) ? 'L' : 'P';
                $mmSize = $this->getSize($resolution, $widht, $height);
                $pdf->AddPage($orientation, $mmSize);
                $name = uniqid() . '.png';
                $im->writeImage($name);
                $im->destroy();
                $pdf->Image($name, 0, 0, $mmSize[0]);
                unlink($name);
            }
        }
        $fileName = tempnam('images/temp/', 'pdf') . '.pdf';
        $pdf->Output($fileName, 'F');
        echo $fileName;
    }

    public function actionGetPdf() {
        $fileName = $_GET['fileName'];
        $this->output_file($fileName, uniqid() . '.pdf', 'pdf');
    }

    private function getSize($resolution, $widht, $height) {
        $sizeMM = array();
        array_push($sizeMM, ($widht * 25.4) / $resolution['x']);
        array_push($sizeMM, ($height * 25.4) / $resolution['y']);
        return $sizeMM;
    }

    /**
     * Dibuja
     * 
     *  la marca de agua en la imagen.
     * @param string $filePath
     * @param string $docType
     * @return Imagick::Object
     * @author GDM
     */
    protected function setWaterMark($filePath, $doc) {
        $docType = DocTypes::model()->getDocumentByDocTypeDesc($doc);
        $this->filePath = $filePath;
        $path = $this->getValidFile();
        $im = new Imagick($path);
        try {
            $outputtype = $im->getFormat();
            $size = $im->getImageLength();
            if ($docType->water_mark_text != null) {
                $draw = new ImagickDraw();
                $draw->setFontSize($docType->water_mark_font_size);
                $draw->setFillOpacity($docType->water_mark_opacity);
                $draw->setGravity(Imagick::GRAVITY_CENTER);
                $im->annotateImage($draw, 0, 0, $docType->water_mark_angle, $docType->water_mark_text);
            }
            return $im;
        } catch (Exception $e) {
            $message = $e->getMessage();
        }
    }

    /**
     * Devuelve la ruta del xml.
     * @param string $path
     * @return string
     * @author GDM
     */
    private function getDestination($IDC) {
        $tempRelativePath = self::OZ_DIRECTORY;
        $tempAbsolutePath = dirname(Yii::app()->request->scriptFile) . '/' . $tempRelativePath;
        $this->filePath = $this->getValidFile();
        $fileInfo = pathinfo($this->filePath);
        $destination = $tempRelativePath . $IDC . '_' . $fileInfo['filename'] . '.xml';
        if (!file_exists($destination)) {
            $this->createOZStructure($destination, $fileInfo, $tempAbsolutePath, $tempRelativePath);
        }
        return $destination;
    }

    /**
     * Revisa la ruta, determinado si el sistema operativo es Windows o Linux.
     * Devuelve la ruta valida.
     * @return string Ruta válida.
     * @author GDM
     */
    private function getValidFile() {
        $pathW = str_replace('|', '\\', $this->filePath);
        $pathL = str_replace('|', '/', $this->filePath);
        return (file_exists($pathW)) ? $pathW : $pathL;
    }

    private function createOZStructure($destination, $fileInfo, $tempAbsolutePath, $tempRelativePath) {
        set_time_limit(0);
        if (strcasecmp($fileInfo['extension'], 'jpg') != 0 && strcasecmp($fileInfo['extension'], 'png') != 0) {
            $im = new Imagick();
            $im->readImage($this->filePath);
            $im->setFormat('jpg');
            $im->setImageCompressionQuality(100);
            ;
            $tempFile = $tempAbsolutePath . $fileInfo['filename'] . '.jpg';
            if ($im->writeImage($tempFile)) {
                $source = $tempRelativePath . $fileInfo['filename'] . '.jpg';
                $this->newOZConverter($source, $destination, TRUE);
            }
            $im->destroy();
        } else {
            $source = $this->filePath; //$tempAbsolutePath.$fileInfo['basename'];
            $this->newOZConverter($source, $destination);
        }
    }

    /**
     * Crea la estructura piramidal de Open Zoom.
     * @param string $source Ruta origen de la imagen jpg o png.
     * @param string $destination Ruta destino del xml.
     * @param bool $delete Borra el temporal creado.
     * @author GDM
     */
    private function newOZConverter($source, $destination, $delete = false) {
        $converter = new Oz_Deepzoom_ImageCreator();
        $converter->create($source, $destination);
        if ($delete)
            unlink($source);
    }

}
