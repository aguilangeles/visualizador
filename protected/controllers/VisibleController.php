<?php

include('GetGroupController.php');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ToogleCaratVisibilityController
 *
 * @author aguilangeles@gmail.com
 */
class VisibleController extends Controller{

    public function actionToggleCaratVisibility() {
        $result = array('message', 'data');
        $docType = null;
        $action = $_POST["action"];
        $conditions = json_decode($_POST["query"]);
        $fields = json_decode($_POST["fields"]);
        $c = Condition::setConditions($conditions);
        foreach ($fields as $field) {
            $docType = DocTypes::getDocumentByDocTypeDesc($field->doc);
            break;
        }
        $modifier = new EMongoModifier();
        if ($action == "show") {
            $modifier->addModifier('visibleCarat', 'set', TRUE);
            $modifier->addModifier('visibleImagen', 'set', TRUE);
            if (Idc::model()->updateAll($modifier, $c)) {
                $result['message'] = "Se muestran exitosamente todos los documentos.";
            }
        } else {
            $modifier->addModifier('visibleCarat', 'set', FALSE);
            $modifier->addModifier('visibleImagen', 'set', FALSE);
            if (Idc::model()->updateAll($modifier, $c)) {
                $result['message'] = "Se ocultaron exitosamente todos los documentos.";
            }
        }
        $getGroupCont = new GetGroupController();
        $group = $getGroupCont->getGroup($c, $docType, $conditions, null, 'images', $fields);
        $result['image'] = json_encode($group['data']['retval'][0]['images']);
        echo CJSON::encode($result);
    }

}
