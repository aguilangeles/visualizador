<?php

class DocumentsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('index','import','delete','deleteIDC','importImagesIndex', 'importImages','searchMeta'),
				'expression'=>'(isset(Yii::app()->user->isAdmin)?Yii::app()->user->isAdmin:false)',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionIndex()
	{
		$model = new Idc();
		$idcs = array();
		$searchType = 0;
		$docType = 0;
		$metaColumns = array();
		$fromDate = null;
		$toDate = null;
		$metaVal = null;
		$name = null;

		$docTypes = DocTypes::model()->findAll();
		$docsArray = array("Todos" => "Todos");

		foreach ($docTypes as $doc) {
			$docsArray[$doc->doc_type_desc] = $doc->doc_type_label;
		}

		if(isset($_POST['docType']))
		{
			$docType = $_POST['docType'];

			if($docType != 'Todos')
			{
				$docTypeMon = DocTypes::getDocumentByDocTypeDesc($docType);

				foreach($docTypeMon->Carats as $carat)
				{
					array_push($metaColumns, array('name' => $carat->carat_meta_label, 
													'value' => '$data->CMETA_'.$carat->carat_meta_desc));
				}
			}
		}		

		if(isset($_POST['searchType']))
		{
			$searchType = $_POST['searchType'];

			if($searchType == 0)
			{
				//Buscamos por fecha
				if(isset($_POST['fromDate']))
				{
					$fromDate = $_POST['fromDate'];
				}

				if(isset($_POST['toDate']))
				{
					$toDate = $_POST['toDate'];	
				}

				if(!empty($fromDate) or !empty($toDate))
					$idcs = Idc::model()->populateRecords($this->getIDCsByDate($fromDate, $toDate, $docType));
			}
			elseif($searchType == 1)
			{
				// Buscamos por Meta
				if(isset($_POST['metaVal']))
				{
					$metaVal = $_POST['metaVal'];
				}

				if(!empty($metaVal))
					$idcs = Idc::model()->populateRecords($this->getIDCsByMeta($metaVal, $docType));
			}
			else
			{
				// Buscamos por Nombre
				if(isset($_POST['name']))
				{
					$name = $_POST['name'];
				}


				if(!empty($name))
					$idcs = Idc::model()->populateRecords($this->getIDCsByname($name, $docType));
			}
		}

		$this->layout = 'main';
		$this->render('index',array(
			'model'=> $model,
			'idcs' => $idcs,
			'fromDate' => $fromDate,
			'toDate' => $toDate,
			'metaVal' => $metaVal,
			'name' => $name,
			'searchType' => $searchType,
			'docTypes' => $docsArray,
			'docType' => $docType,
			'metaColumns' => $metaColumns,
		));
	}

	protected function getIDCsByDate($fromDate, $toDate, $docType)
	{            
		$keys = array('docType' => 1,'IDC' => 1,'Creation_date' => 1);

		$criteria = new EMongoCriteria();	

		$fromDate = str_replace('/', '-', $fromDate);
		$toDate = str_replace('/', '-', $toDate);

		if($docType != "Todos")
		{
			$docTypeMon = DocTypes::getDocumentByDocTypeDesc($docType);
			$criteria->addCond('docType','==',$docType);

			foreach($docTypeMon->Carats as $carat)
			{
				$keys['CMETA_'.$carat->carat_meta_desc] = 1;
			}
		}

       	if(!empty($fromDate))
			$criteria->Creation_date('>=', new MongoDate(strtotime($fromDate)));

		if(!empty($toDate))
			$criteria->Creation_date('<=', new MongoDate(strtotime($toDate . ' 23:59:59')));	
                      
        $initial = array("items" => array());
                       
        $reduce = "function (obj, prev) {}";

        $result = Idc::model()->group($keys, $initial, $reduce, $criteria);

        return array_slice($result["retval"], 0, 100);
    }	

   	protected function getIDCsByName($name, $docType)
	{            
        $keys = array('docType' => 1,'IDC' => 1,'Creation_date' => 1);

		$criteria = new EMongoCriteria();	

		if($docType != "Todos")
		{
			$docTypeMon = DocTypes::getDocumentByDocTypeDesc($docType);
			$criteria->addCond('docType','==',$docType);

			foreach($docTypeMon->Carats as $carat)
			{
				$keys['CMETA_'.$carat->carat_meta_desc] = 1;
			}
		}

		$criteria->IDC = new MongoRegex("/" . $name . "/i");	
                      
        $initial = array("items" => array());
                       
        $reduce = "function (obj, prev) {}";

        $result = Idc::model()->group($keys, $initial, $reduce, $criteria);

        return array_slice($result["retval"], 0, 100);
    }	

    protected function getIDCsByMeta($metaVal, $docType)
	{            
        $keys = array('docType' => 1,'IDC' => 1,'Creation_date' => 1);

		$criteria = new EMongoCriteria();	

		$docs = DocTypes::model()->findAll();
		$carats = null;//$this->getCaratMeta($docsLevel1);
		$searchType = $_POST['searchType'];

		if($docType != "Todos")
		{
			$docTypeMon = DocTypes::getDocumentByDocTypeDesc($docType);
			$criteria->addCond('docType','==',$docType);

			foreach($docTypeMon->Carats as $carat)
			{
				$keys['CMETA_'.$carat->carat_meta_desc] = 1;
				$query = new MongoRegex('/'.$metaVal.'/i');
				$criteria->addCond('CMETA_'.$carat->carat_meta_desc,'or',$query); 
			}
		}
		else
		{
			foreach ($docs as $doc)
			{
				foreach($doc->Carats as $carat)
				{
					$query = new MongoRegex('/'.$metaVal.'/i');
					$criteria->addCond('CMETA_'.$carat->carat_meta_desc,'or',$query);                                                                                  
				}
		    }
	    }               	

        $initial = array("items" => array());
                       
        $reduce = "function (obj, prev) {}";

        $result = Idc::model()->group($keys, $initial, $reduce, $criteria);

        return array_slice($result["retval"], 0, 100);
    }

	public function actionImport()
	{
		$result = '';

		try
		{
			if(isset($_POST['path']))
			{
				$result = self::importIDC($_POST['path']);
			}

			echo $result;
		}
        catch (Exception $e)
        {
            echo $e->getMessage(); 
        }		
	}

	public function importIDC($path)
	{
		$idc = new Idc();

		$command = "java -jar /var/www/utn-visu/cmd.jar import";
		$command .= " " . str_replace("mongodb://", "", Yii::app()->mongodb->connectionString); 
		$command .= " " . Yii::app()->mongodb->dbName;
		$command .= " " . $idc->getCollectionName();

		$path = str_replace("\\", '/', $path);

		if($path[strlen($path) - 1] == '/')
			$path = substr($path, 0, strlen($path) - 1);

		if(file_exists($path.'/IDC.xml'))
		{
			$path_exploted = explode('/', $path);
			unset($path_exploted[count($path_exploted)-1]);
			$path_choped = implode('/', $path_exploted);
		}
		else 
		{
			$path_choped = $path;
		}

		
		$command .= " " . $path . " ".$path_choped;
		
		return shell_exec($command);
	}

	public function actionImportImagesIndex()
	{
		$docTypes = DocTypes::model()->findAll();
		$docsArray = array("Seleccione un Tipo de Documento");

		foreach ($docTypes as $doc) {
			array_push($docsArray, $doc->doc_type_desc);
		}

		$this->layout = 'main';
		$this->render('importImagesIndex',array(
			'docTypes'=> $docsArray,
		));
	}

	public function actionImportImages()
	{
		$imgQty = 0;
		$fromPath = "";
		$toPath = "";
		$metas = array();
		$docType = DocTypes::getDocumentByDocTypeDesc($_POST['docType']);
		$subType = "Web";
                
		try
		{
			if(isset($_POST['fromPath']))
			{
				$fromPath = str_replace("\\", '/', $_POST['fromPath']);
				if(!file_exists($fromPath))
				{
					echo "Path de origen no Accesible.";
					return;
				}
				else
				{
					$fi = new FilesystemIterator($fromPath, FilesystemIterator::SKIP_DOTS);
					foreach ($fi as $file) {
						if($file->isFile())
						{
							$imgQty++;
						}
					}
				}
			}

			if(isset($_POST['toPath']))
			{
				$toPath = str_replace("\\", '/', $_POST['toPath']);
				if(!file_exists($toPath))
				{
					if(!is_writable($toPath))
					{
						echo "Path de destino no Accesible.";
						return;
					}
				}
			}

			if(isset($_POST['metas']))
			{
				$metas = explode(",", $_POST['metas']);
			}

			$idcPath = $_POST['name'];

			if(isset($_POST['plano']))
			{
				if($_POST['plano'] == "true")
				{
					$subType = 'OZ';
					$idcPath = str_replace("#", '', $idcPath);
				}
			}

			if(!file_exists($toPath . '/' . $idcPath))
			{
				mkdir($toPath . '/' . $idcPath . '/');
			}
			else
			{
				echo "El directorio de destino ya existe.";
				return;
			}

			//GUID
			if(isset($_POST['idIDC']))
			{
				$id = $_POST['idIDC'];
			}
			else
			{
				echo "Id de IDC no informado";
				return;
			}

			echo 'Generando XMLs' , PHP_EOL;

			$XmlIDCs = Xml::IdcXML($idcPath, $imgQty);
			$XmlCaptura = Xml::CapturaXML($idcPath);
			$XmlCarat = Xml::CaratXML($idcPath, $docType, $subType, $metas, $id);
			$XmlMapeo = Xml::MapeoXML($idcPath, $docType, $fromPath, $id);
			$XmlMeta = Xml::MetaXML($idcPath, $docType, $fromPath, $id);
			$XmlTraza = Xml::TrazaXML($idcPath);

			$XmlIDCs->asXml($toPath . '/' . $idcPath . '/IDC.xml');
			$XmlCaptura->asXml($toPath . '/' . $idcPath . '/Captura.xml');
			$XmlCarat->asXml($toPath . '/' . $idcPath . '/Carat.xml');
			$XmlMapeo->asXml($toPath . '/' . $idcPath . '/Mapeo.xml');
			$XmlMeta->asXml($toPath . '/' . $idcPath . '/Meta.xml');
			$XmlTraza->asXml($toPath . '/' . $idcPath . '/Traza.xml');

			echo 'Copiando Imagenes', PHP_EOL;

			$pathImagenes = $toPath . '/' . $idcPath . '/' . 'Imagenes/';
			mkdir($pathImagenes);
			foreach ($fi as $file) {
				if($file->isFile())
				{
					copy($file->getPathname(), $pathImagenes . $file->getFilename());
				}
			}

			echo 'Guardando en Mongo', PHP_EOL;

			echo self::importIDC($toPath . '/' . $idcPath);
        }
        catch (Exception $e)
        {
            echo $e->getMessage(); 
        }

	}

	public function actionSearchMeta()
	{
		$result = "";
      	$docType = DocTypes::getDocumentByDocTypeDesc($_POST['docType']);

      	foreach($docType->Carats as $carat)	{
      		$result .= "<div>";
    		$result .= '<label>'.$carat->carat_meta_label.'</label>';
    		$result .= '<input type="text" id="'.$carat->carat_meta_desc.'" value="" class="metas"></>';
    		$result .= "</div>";
    	}

		echo $result;
	}

	public function actionDelete()
	{
		$IDCs = null;
		$criteria = new EMongoCriteria();	
		$strIdc = null;

		try
		{
			if(isset($_POST['IDCs']))
			{
				foreach ($_POST['IDCs'] as $id_idc)
				{
					$criteria->IDC('==', $id_idc);
	    			Idc::model()->deleteAll($criteria);
				}
			}

			echo count($_POST['IDCs']) . " registros eliminados.";
		}
        catch (Exception $e)
        {
            echo $e->getMessage(); 
        }
	}

	public function actionDeleteIDC()
	{
		$criteria = new EMongoCriteria();	
		$strIdc = null;
		$idc = null;
		$result = "IDC no encontrado";

		try
		{
			if(isset($_POST['idc']))
			{
				$strIdc = $_POST['idc'];
				$criteria->IDC('==', $strIdc);

				$idc = Idc::model()->find($criteria);

				if($idc != null)
				{
					Idc::model()->deleteAll($criteria);
					$result = "IDC eliminado.";
				}
			}

			echo $result;
		}
        catch (Exception $e)
        {
            echo $e->getMessage(); 
        }
	}


	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='ocr-meta-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
