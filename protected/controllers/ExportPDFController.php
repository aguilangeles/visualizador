<?php

include('Funciones.php');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExportPDFController
 *
 * @author aguilangeles@gmail.com
 */
class ExportPDFController extends Controller {

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
                $imwat = new Funciones();
                $im = $imwat->setWaterMark($pathW, $image['docType']);
            } else if (file_exists($pathL)) {
                $imwat = new Funciones();
                $im = $imwat->setWaterMark($pathL, $image['docType']);
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
        $out = new Funciones();
        $out->output_file($fileName, uniqid() . '.pdf', 'pdf');
    }

    private function getSize($resolution, $widht, $height) {
        $sizeMM = array();
        array_push($sizeMM, ($widht * 25.4) / $resolution['x']);
        array_push($sizeMM, ($height * 25.4) / $resolution['y']);
        return $sizeMM;
    }

}
