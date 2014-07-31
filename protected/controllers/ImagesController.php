<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GetImagesByIdController
 *
 * @author aguilangeles@gmail.com
 */
class ImagesController extends Controller {

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
	    
            $content = $content . '<div style="height:400px;position:relative;overflow:auto;">';
	    
            $content = $content . '<table id="' . $items['id'] . '" class="tablesorter" style="table-layout: fixed; word-wrap:break-word;"><thead><tr>';
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

}
