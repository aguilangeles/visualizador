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

    /**
     * Revisa la ruta, determinado si el sistema operativo es Windows o Linux.
     * Devuelve la ruta valida.
     * @return string Ruta válida.
     * @author GDM
     */
//    private function getValidFile() {
//        $pathW = str_replace('|', '\\', $this->filePath);
//        $pathL = str_replace('|', '/', $this->filePath);
//        return (file_exists($pathW)) ? $pathW : $pathL;
//    }

    private function getDestination($IDC) {
        $tempRelativePath = self::OZ_DIRECTORY;
        $tempAbsolutePath = dirname(Yii::app()->request->scriptFile) . '/' . $tempRelativePath;
        $validfile = new Funciones();
        $this->filePath = $validfile->getValidFile();
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
//
//    /*function output_file($file, $name, $mime_type = '') {
//        /*
//          This function takes a path to a file to output ($file),
//          the filename that the browser will see ($name) and
//          the MIME type of the file ($mime_type, optional).
//
//          If you want to do something on download abort/finish,
//          register_shutdown_function('function_name');
//         */
//        if (!is_readable($file))
//            die('File not found or inaccessible!');
//
//        $size = filesize($file);
//        $name = rawurldecode($name);
//
//        /* Figure out the MIME type (if not specified) */
//        $known_mime_types = array(
//          "pdf" => "application/pdf",
//          "txt" => "text/plain",
//          "html" => "text/html",
//          "htm" => "text/html",
//          "exe" => "application/octet-stream",
//          "zip" => "application/zip",
//          "doc" => "application/msword",
//          "xls" => "application/vnd.ms-excel",
//          "ppt" => "application/vnd.ms-powerpoint",
//          "gif" => "image/gif",
//          "png" => "image/png",
//          "jpeg" => "image/jpg",
//          "jpg" => "image/jpg",
//          "php" => "text/plain"
//        );
//
//        if ($mime_type == '') {
//            $file_extension = strtolower(substr(strrchr($file, "."), 1));
//            if (array_key_exists($file_extension, $known_mime_types)) {
//                $mime_type = $known_mime_types[$file_extension];
//            } else {
//                $mime_type = "application/force-download";
//            };
//        };
//
//        @ob_end_clean(); //turn off output buffering to decrease cpu usage
//        // required for IE, otherwise Content-Disposition may be ignored
//        if (ini_get('zlib.output_compression'))
//            ini_set('zlib.output_compression', 'Off');
//
//        header('Content-Type: ' . $mime_type);
//        header('Content-Disposition: attachment; filename="' . $name . '"');
//        header("Content-Transfer-Encoding: binary");
//        header('Accept-Ranges: bytes');
//
//        /* The three lines below basically make the 
//          download non-cacheable */
//        header("Cache-control: private");
//        header('Pragma: private');
//
//        // multipart-download and download resuming support
//        if (isset($_SERVER['HTTP_RANGE'])) {
//            list($a, $range) = explode("=", $_SERVER['HTTP_RANGE'], 2);
//            list($range) = explode(",", $range, 2);
//            list($range, $range_end) = explode("-", $range);
//            $range = intval($range);
//            if (!$range_end) {
//                $range_end = $size - 1;
//            } else {
//                $range_end = intval($range_end);
//            }
//
//            $new_length = $range_end - $range + 1;
//            header("HTTP/1.1 206 Partial Content");
//            header("Content-Length: $new_length");
//            header("Content-Range: bytes $range-$range_end/$size");
//        } else {
//            $new_length = $size;
//            header("Content-Length: " . $size);
//        }
//
//        /* output the file itself */
//        $chunksize = 1 * (1024 * 1024); //you may want to change this
//        $bytes_send = 0;
//        if ($file = fopen($file, 'r')) {
//            if (isset($_SERVER['HTTP_RANGE']))
//                fseek($file, $range);
//
//            while (!feof($file) &&
//            (!connection_aborted()) &&
//            ($bytes_send < $new_length)
//            ) {
//                $buffer = fread($file, $chunksize);
//                print($buffer); //echo($buffer); // is also possible
//                flush();
//                $bytes_send += strlen($buffer);
//            }
//            fclose($file);
//            unlink($file);
//        } else
//            die('Error - can not open file.');
//
//        die();
//    }

}
