<?php

/**
 * Devuelve el encabezado de la búsqueda.
 * @param type $group
 * @param type $currentPage
 * @param type $docsLevel
 * @param type $fields
 * @return string 
 */

/**
 * Description of GetContentResultController
 *
 * @author aguilangeles@gmail.com
 */
class GetContentResultController extends Controller {

    function GetContentResultController() {
        
    }

    public
        function getContentResult($result, $currentPage, $docsLevel, $fields, $view = 'results', $groupBy = 'carat') {
        if ($result['data']['ok']) {
            if ($result['data']['count'] > 10000) {
                $content = '<div class="errorMessage">Se encontraron mas de 10.000 coincidencias, Por favor, refine aún mas su busqueda.</div>';
            } else {
                if ($result['data']['count'] == 0) {
                    $content = '<div class="errorMessage"><img src="../images/error.png" style="height:25px;margin-bottom:-6px;"> No se encontraron resultados.</div>';
                } else {
                    if ($result['data'] != null) {
                        $finded = (int) $result['data']["keys"];
                        if ($finded == 0) {
                            $content = '<div class="errorMessage"><img src="../images/error.png" style="height:25px;margin-bottom:-6px;"> No se encontraron resultados.</div>';
                        } else {
                            $content = '<div class="okMessage"><img src="../images/ok.png" style="height:25px;margin-bottom:-6px;">Se encontraron ' . $finded . ' resultados</div>';
                            $pages = ceil($finded / Idc::PAGE_SIZE);
                            $content = $content . $this->renderPartial($view, array('pages' => $pages, 'currentPage' => $currentPage, 'group' => $result, 'docsLevel' => $docsLevel, 'fields' => $fields, 'groupBy' => $groupBy), true, false);
                        }
                    } else {
                        $content = '<div class="errorMessage"><img src="../images/error.png" style="height:25px;margin-bottom:-6px;">  Por favor, filtre aún mas su busqueda.</div>';
                    }
                }
            }
        } else {
            if ($result["data"]["code"] == 10334) {
                $content = '<div class="errorMessage">Se encontraron mas de 10.000 coincidencias, Por favor, refine aún mas su busqueda.</div>';
            } else {
                $content = '<div class="errorMessage"><img src="../images/error.png" style="height:25px;margin-bottom:-6px;">  ' . $result["data"]["errmsg"] . '.</div>';
            }
        }
        return $content;
    }

}
