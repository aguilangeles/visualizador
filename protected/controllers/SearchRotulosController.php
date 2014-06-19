<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SearchRotulosController
 *
 */
class SearchRotulosController extends Controller {

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

}
