<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SeeMoreController
 *
 * @author aguilangeles@gmail.com
 */
class MoreController extends Controller {

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

}
