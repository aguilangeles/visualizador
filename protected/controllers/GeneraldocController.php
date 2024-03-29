<?php

include('GetDocsGeneralByTypeController.php');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SearchGeneralDoc
 *
 * @author nonames@gmail.com
 */
class GeneraldocController extends Controller
{

	public function actionSearchGeneralDoc($currentPage = 1, $currentdoc = null)
	{
		$docNameArray = array();
		$docIdArray = array();
		$conditions = array();
		$ocrs = null; //$this->getOcrMeta($docsLevel1);
		$carats = null; //$this->getCaratMeta($docsLevel1);
		
		$c = new EMongoCriteria;
		if (!Yii::app()->user->isAdmin) { //--> condiciones para el usuario admin
			$c->addCond('visibleCarat', '==', TRUE);
			$condition = new Condition('visibleCarat', '==', TRUE);
			array_push($conditions, $condition);
			$c->addCond('visiblePapel', '==', TRUE);
			$condition = new Condition('visiblePapel', '==', TRUE);
			array_push($conditions, $condition);
			$c->addCond('visibleImagen', '==', TRUE);
			$condition = new Condition('visibleImagen', '==', TRUE);
			array_push($conditions, $condition);
		}
		$currentPage = $_POST['page'];
		$currentdoc = $_POST['docType'];
//<<<<<<< HEAD
////		$docsLevel1 = Users::getAllDocTypes((int) Yii::app()->user->id, 1);//no usado
//=======
//>>>>>>> 20140902

		$searchType = $_POST['searchType'];
		$searchText = $_POST['field'];
		$docs = explode(',', $_POST['docs']);//--> trae los elementos seleccionados 
		$hasSpecialField = false;
		foreach ($docs as $doc) {
			$fields = array();
			$docType = DocTypes::model()->findByPk($doc);
			foreach ($docType->Carats as $carat) {
				array_push($fields, Field::getField($carat->carat_meta_desc, 'CARAT', $docType->doc_type_desc));
				if ($carat->is_special) {
					$hasSpecialField = TRUE;
					array_push($docNameArray, $docType->doc_type_desc);
					array_push($docIdArray, $docType->doc_type_id);
					if ($searchType == "Parecida") {
						$query = new MongoRegex('/' . $searchText . '/i');
						$c->addCond('CMETA_' . $carat->carat_meta_desc, 'or', $query);
						$condition = new Condition('CMETA_' . $carat->carat_meta_desc, 'regex', $searchText);
					} else {
						$c->addCond('CMETA_' . $carat->carat_meta_desc, 'or', $searchText);
						$condition = new Condition('CMETA_' . $carat->carat_meta_desc, 'or', $searchText);
					}
					array_push($conditions, $condition);
				}
			}
			foreach ($docType->OCRs as $ocr) {
				if ($ocr->is_special) {
					$hasSpecialField = TRUE;
//<<<<<<< HEAD
					array_push($docNameArray, $docType->doc_type_desc);
					array_push($docIdArray, $docType->doc_type_id);
//=======
//					array_push($arraydocs, $docType->doc_type_desc);
//					array_push($arraydocs_id, $docType->doc_type_id);
//>>>>>>> 20140902
					if ($searchType == "Parecida") {
						$query = new MongoRegex('/' . $searchText . '/i');
						$c->addCond('OCR_' . $ocr->ocr_meta_desc, 'or', $query);
						$condition = new Condition('OCR_' . $ocr->ocr_meta_desc, 'regex', $query);
					} else {
						$c->addCond('OCR_' . $ocr->ocr_meta_desc, 'or', $searchText);
						$condition = new Condition('OCR_' . $ocr->ocr_meta_desc, 'or', $searchText);
					}
					array_push($conditions, $condition);
				}
			}
		}
		
//<<<<<<< HEAD
		$c->addCond("docType", 'in', $docNameArray);
		$condition = new Condition("docType", 'in', $docNameArray);
//=======
//		$c->addCond("docType", 'in', $arraydocs);
//		$condition = new Condition("docType", 'in', $arraydocs);
//>>>>>>> 20140902
		array_push($conditions, $condition);
		$c->limit(Idc::PAGE_SIZE);
		$c->offset(($currentPage - 1) * Idc::PAGE_SIZE);
		if ($hasSpecialField) {
			$getDocsGenByType = new GetDocsGeneralByTypeController();
//<<<<<<< HEAD
			echo $getDocsGenByType->getDocsGeneralByType($c, $conditions, $docs, $currentdoc, $docIdArray);
//=======
//			echo $getDocsGenByType->getDocsGeneralByType($c, $docType, $conditions, $docs, $currentdoc, $arraydocs_id);
//>>>>>>> 20140902
		} else {
			echo '<div class="errorMessage"><img src="../images/error.png" '
			. 'style="height:25px;margin-bottom:-6px;"> No hay definido ningún campo especial. '
			. 'Por favor, configure un campo especial antes de usar esta búsqueda.</div>';
		}
	}
}
