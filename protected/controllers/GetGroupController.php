<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GetGroupController
 *
 * @author aguilangeles@gmail.com
 */
class GetGroupController extends Controller {

    function GetGroupController(){
    
    }
    public function getGroup($criteria, $doctype, $conditions, $docsLevel = 'c1', $groupBy = 'carat', $fields = null) {
        $keys = array('docType');
        if ($groupBy == 'carat') {
            $keys = array($docsLevel, 'docType');
        }
        foreach ($fields as $field) {
            array_push($keys, $field->prefix . $field->name);
        }
        $c = '';
        $reduce5 = '';
        $carats = DocTypes::model()->getMetaCarats($doctype->doc_type_id);
        foreach ($carats as $carat) {
            $reduce5 = $reduce5 . 'prev.images.push(obj.' . 'CMETA_' . $carat . ');';
            $c = $c . $carat . ",";
        }
        $reduce5 = 'prev.images.push("' . $c . '");' . $reduce5;
        $keys = array_flip($keys);
        $initial = array("images" => array(), "index" => 0, "info" => array());
        $reduce1 = "function (obj, prev) { ";
        $reduce2 = 'prev.images.push(obj._id);';
        $reduce3 = 'prev.info.push(obj.order);prev.images.push(obj.docType);prev.images.push(obj.docSubtipo);prev.images.push(obj.visibleCarat);prev.images.push(obj.order);prev.images.push(obj.visibleImagen);prev.images.push(obj.fileName);prev.images.push(obj.filePath);prev.images.push(obj.face);prev.images.push(obj.idPapel);prev.images.push(obj.IDC);';
        $reduce4 = '';
        $o = "";
        $ocrs = DocTypes::model()->getMetaOcrs($doctype->doc_type_id);
        if ($ocrs != null) {
            foreach ($ocrs as $ocr) {
                $reduce4 = $reduce4 . 'prev.images.push(obj.' . 'OCR_' . $ocr . ');';
                $o = $o . $ocr . ",";
            }
        }

        $reduce4 = 'prev.images.push("' . $o . '");' . $reduce4;
        $reduce6 = 'prev.index += 1;}';
        $reduce = $reduce1 . $reduce2 . $reduce3 . $reduce5 . $reduce4 . $reduce6;
        $group = Idc::model()->group($keys, $initial, $reduce, $criteria);
        $result = array('keys' => $conditions, 'data' => $group);

        return $result;
    }

}
