<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SearchGeneralController
 *
 * @author aguilangeles@gmail.com
 */
class SearchGeneralController extends Controller {

    public function actionSearchGeneral() {
        $result = "";
        $docsLevel1 = Users::getAllDocTypes((int) Yii::app()->user->id, 1);
        echo CHtml::checkBoxList('DocRest', array_keys($docsLevel1), $docsLevel1, array('labelOptions' => array('style' => 'text-align:left;width:100px;')));
    }

}
