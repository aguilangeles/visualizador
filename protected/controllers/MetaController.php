<?php

/**
 * Description of SearchMetaCaratController
 *
 */
class MetaController extends Controller {

     public function actionSearchMetaCarat() {
        if (isset($_POST['docLevel1'])) {
            $docLevel1 = (int) $_POST['docLevel1'];
            $docLevel2 = (int) $_POST['docLevel2'];
            $docLevel3 = (int) $_POST['docLevel3'];
            $docLevel4 = (int) $_POST['docLevel4'];
            $docs = array($docLevel1, $docLevel2, $docLevel3, $docLevel4);
            $OcrContent = '';
            $docsLevels = array();
            for ($i = 0; $i < 4; $i++) {
                if ($docs[$i] != 0) {
                    $doc = DocTypes::model()->findByPk($docs[$i]);
                    $docsLevels = $docsLevels + array($doc->doc_type_id => $doc->doc_type_desc);
                }
            }
            $content = '<div id="filtersCarat"><fieldset class="form" style="width:260px;">
				<legend>Metadata de carátula</legend><div id="MetaCarats" style="width: 260px;">';
            foreach ($docsLevels as $docL) {
                $document = DocTypes::model()->find('doc_type_desc = :doc', array(':doc' => $docL));
                foreach ($document->Carats as $carat) {
                    $content = $content . '<div class="level-tag" style="width:260px">' . $carat->carat_meta_label . '</div>';
                    $content = $content . '<div class="level-tag" style="width:260px">' . CHtml::textField('CMETA_[]') . '</div>';
                }
                foreach ($document->OCRs as $ocr) {
                    $OcrContent = $OcrContent . '<div class="level-tag" style="width:260px">' . $ocr->ocr_meta_label . '</div>';
                    $OcrContent = $OcrContent . '<div class="level-tag" style="width:260px">' . CHtml::textField('OCR_[]', '', array('class' => 'metadata')) . '</div>';
                }
            }
            $content = $content . '</div></fieldset></div><div id="filtersOCR"><fieldset class="form" style="width:260px;">
					<legend>Metadata de imagen</legend><div id="MetaOCRs" style="width: 260px;">' . $OcrContent . '</div></fieldset></div>';
            echo $content;
    }
}
}