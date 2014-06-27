<?php


/**
 * Description of ViewImageController
 *
 */
class ViewImageController extends Controller {

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
