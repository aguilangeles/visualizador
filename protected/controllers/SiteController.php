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
//            $doc = $user->getGroups();
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


    /**
     * Acción muestra imagen. 
     */
   public function actionViewImage() {

        $this->layout = self::EMPTY_LAYOUT;
        $widthSize = ((int) $_POST['widthsize'] - 40);
        $rotar = ((int) $_POST['rotar']);
        $imageList = get_object_vars(json_decode($_POST['imageList2']));
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
