<?php

include ('Funciones.php');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExportZipController
 *
 * @author aguilangeles@gmail.com
 */
class ExportZIPController extends Controller {
    /* Constante que define un layout vacío.
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

    public function actionGetZip() {
        $fileName = $_GET['fileName'];
        $out = new Funciones();
        $out->output_file($fileName, uniqid() . '.zip', 'zip');
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
     * Revisa la ruta, determinado si el sistema operativo es Windows o Linux.
     * Devuelve la ruta valida.
     * @return string Ruta válida.
     * @author GDM
     */
    public function getValidFile() {
        $pathW = str_replace('|', '\\', $this->filePath);
        $pathL = str_replace('|', '/', $this->filePath);
        return (file_exists($pathW)) ? $pathW : $pathL;
    }

}
