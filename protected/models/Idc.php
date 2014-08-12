<?php
class Idc extends EMongoSoftDocument
    {
		const PAGE_SIZE = 20;
		public $id;
		public $_document=array();
		public $IDC;
		public $ImageQty;
		public $Creation_date;
                public $prevImage = array();
                public $softAttributes = array();
                public $oMeta;
                public $cMeta;
                public $currImageIndex;
                public $currSubIndex;
                public $prevImageIndex;
                public $nextImageIndex;
		public $idPapel;
		public $docType;
                public $docSubtipo;
		public $reverseImage;
		public $fileName;
		public $filePath;
		public $face;

		// This has to be defined in every model, this is same as with standard Yii ActiveRecord
      public static function model($className=__CLASS__)
      {
        return parent::model($className);
      }

	

      // This method is required!
      public function getCollectionName()
      {
        return 'DatosVisu';
      }
 
      public function rules()
      {
        return array(
          // array('IDC, CreationDate', 'required'),
          // array('IDC, CreationDate', 'length', 'max' => 20),
        );
      }

      public function attributeLabels()
      {
        return array(
          'IDC'  => 'Nombre IDC',
          'CreationDate'   => 'Fecha de Creación',
        );
      }
	public static function getImagesQtyByCaratId($id)
	{
		$c = new EMongoCriteria();
		$m = new Idc();
		$c->addCond('idCarat', '==', $id);
		$results =  $m->findAll($c);
		$cursorDoc = $results->getCursor();
		return $cursorDoc->count();
	}
	
	public static function getImagesByCaratId($id)
	{
		$c = new EMongoCriteria();
		$m = new Idc();
		$c->addCond('idCarat', '==', $id);
		return $m->findAll($c);		
	}

        public static function getGroup($criteria,$carats=null,$ocrs=null,$docsLevel1=null)
	{
		$keys = array('c1','docType');
		//$qty =  count($ocrs) + 8;
		//foreach ($carats as $caratM)
		//{
		//	array_push($keys, 'CMETA_'.$caratM);
		//}
		$keys = array_flip($keys);
		$initial = array("images" => array(),"index"=>0,"info"=>array());
		$reduce1 = "function (obj, prev) { ";
		$reduce3 = 'prev.info.push(obj.order);prev.images.push(obj.docType);prev.images.push(obj.docSubtipo);prev.images.push(obj.visibleCarat);prev.images.push(obj.order);prev.images.push(obj.visibleImagen);prev.images.push(obj.fileName);prev.images.push(obj.filePath);';
		$o = "";
//		if ($ocrs != null)
//		{
//			foreach ($ocrs as $ocr)
//			{
//				$reduce3 = $reduce3.'prev.images.push(obj.' .'OCR_'.$ocr.');';
//				$o = $o.$ocr.",";
//			}
//		}
		$reduce3 = $reduce3. 'prev.index += 1;';
		$reduce3 = $reduce3. '}';
		$reduce2 = 'prev.images.push("'.$o.'");';
		$reduce = $reduce1.$reduce2.$reduce3;
                $group = Idc::model()->group($keys, $initial, $reduce, $criteria);
		
		//return $this->orderResults($group);
                return $group;
	}


	public static function isOrdered($idcs, $haystack=array()){
		foreach ($idcs as $idc) {
			if (isset($haystack[$idc->idPapel])){
				if($idc->face == $haystack[$idc->idPapel])
					return false;
			}
			else{
				$haystack[$idc->idPapel] = $idc->face;
			} 	
		}
		return true;
	}

	public static function Sort($query)
	{
		$idcs = array();
		$sum = 0;

        $conditions = Condition::setConditions($query);
        $conditions->sort('CreationDate',  EMongoCriteria::SORT_ASC);

        $images = Idc::model()->findAll($conditions);

        foreach($images as $image)
        {
        	if(!in_array($image->IDC, $idcs))
            	$idcs[] = $image->IDC;
        }               

        foreach ($idcs as $idc) {
       		$criteria = new EMongoCriteria();
        	$criteria->IDC('==', $idc);

        	$imagesIDC = Idc::model()->findAll($criteria);

        	//Actualizar si no es el primero de la lista
        	if(array_search($idc,$idcs) != 0)
        	{
        		$modifier = new EMongoModifier();
                $modifier->addModifier('idPapel', 'inc', $sum);
                $status = Idc::model()->updateAll($modifier, $criteria);
        	}

        	$sum += count($imagesIDC);
        }
    }
}