<?php


/**
 * Description of SearchRotulosController
 *
 */
class Rotulos1Controller extends Controller {

    public function actionSearchRotulos() {
        if (isset($_POST['rotulo_id'])) {
            $content = "";
            $rotuloId = (int) $_POST['rotulo_id'];
            $rotulo = Rotulos::model()->findByPk($rotuloId);
            $docId = $rotulo->Docs[0]->doc_type_id;
            $doc = DocTypes::model()->findByPk($docId);
            foreach ($doc->Carats as $carat) {
                $content = $content . '<div class="level-tag" style="width:260px">' . $carat->carat_meta_label . '</div>';
                $content = $content . '<div class="level-tag" style="width:260px">' . CHtml::textField('CMETA_[]') . '</div>';
            }
            echo $content;
        }
    }

}
