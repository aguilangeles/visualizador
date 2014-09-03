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
 * @author noname@gmail.com
 */
class GetContentResultController extends Controller {

    function GetContentResultController() {
        
    }
    public function getContentResult($result, $currentPage, $docsLevel, $fields,
				  $doctypedes, $view = 'results', $groupBy = 'carat')
	{
		$style = 'style="height:25px;margin-bottom:-6px;">';
		$errormsg = '<div class="errorMessage"><img src="../images/error.png"' . $style;
		$result100000 = '<div class="errorMessage">Se encontraron mas de 10.000 coincidencias, Por favor, refine aún mas su busqueda.</div>';
		$sinResultados = $errormsg . 'No se encontraron resultados.</div>';
		$afineFiltro = $errormsg . 'Por favor, filtre aún mas su busqueda.</div>';
		$resultado = '<div class="okMessage"><img src="../images/ok.png" ' . $style . 'Se encontraron ';

		if ($result['data']['ok']) {
			if ($result['data']['count'] > 10000) {
				$content = $result100000;
			} else {
				if ($result['data']['count'] == 0) {
					$content = $sinResultados;
				} else {
					if ($result['data'] != null) {
						$finded = (int) $result['data']["keys"];
						if ($finded == 0) {
							$content = $sinResultados;
						} else {
// 
							$tipodoc = ($doctypedes == null) ? '.' : ' en ' . $doctypedes;
							$content = $resultado . $finded . ' resultados' . $tipodoc . ' </div>';
							$pages = ceil($finded / Idc::PAGE_SIZE);
							$content = $content . $this->renderPartial($view
								, array('pages' => $pages
							      , 'currentPage' => $currentPage
							      , 'group' => $result
							      , 'docsLevel' => $docsLevel
							      , 'fields' => $fields
							      , 'groupBy' => $groupBy
							      , 'namesdoc' => $doctypedes
								)
								, true
								, false);
						}
					} else {
						$content = $afineFiltro;
					}
				}
			}
		} else {
			if ($result["data"]["code"] == 10334) {
				$content = $result100000;
			} else {
				$content = $errormsg . $result["data"]["errmsg"] . '.</div>';
			}
		}
		return $content;
	}

}
