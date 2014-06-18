<?php

class ShowimageController extends Controller
{

	/**
         * Constante que define un layout vacío.
         */
        const EMPTY_LAYOUT = 'empty_layout';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('view','oz','Users','ChangePassword','ChangePassword2'),
				//'expression'=>'(isset(Yii::app()->user->isAdmin)?Yii::app()->user->isAdmin:false)',
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'expression'=>'(isset(Yii::app()->user->isAdmin)?Yii::app()->user->isAdmin:false)',
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'expression'=>'(isset(Yii::app()->user->isAdmin)?Yii::app()->user->isAdmin:false)',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	public function actionError()
	{
	    if($error==Yii::app()->errorHandler->error)
	    {
	    	if(Yii::app()->request->isAjaxRequest)
	    		echo $error['message'];
	    	else
	        	$this->render('error', $error);
	    }
	}

	public function actionView()
	{
		$this->layout = self::EMPTY_LAYOUT;
		$message = ""; //resultado de la operación.
		$widthSize = ($_GET["widthSize"] == 'auto')? 600 : (int)$_GET["widthSize"];
        $widthSize = $widthSize -100;
		$path = $_GET["path"];
		$docType = $_GET["doc"];
                $docSubtype = $_GET["subdoc"];
		$c = new CDbCriteria();
		$c->params = array(':doc_type_desc'=>$docType);
		$c->condition = 'doc_type_desc = :doc_type_desc';
		$doc = DocTypes::model()->find($c);
                $exits = (file_exists($path));
                if (!$exits)
                {                                            
                    $path = dirname(Yii::app()->request->scriptFile).DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."nodisponible.png";
                    //$message = 'No se encontró la imagen.';
                }                             
                try
                {
                    $im = new Imagick($path);
                    $im->setFormat('png');
                    $im->setImageDepth(24); // define la profundidad de color de la imagen en 24bits
                    //$depth = $im->getImageDepth(); //consulta la profundidad actual
                    $outputtype = $im->getFormat();
                    $size=$im->getImageLength();
                    if ($doc->water_mark_text != null && $exits)
                    {
                            $draw = new ImagickDraw();
                            $draw->setFontSize($doc->water_mark_font_size );
                            $draw->setFillOpacity($doc->water_mark_opacity );
                            $draw->setGravity( Imagick::GRAVITY_CENTER );
                            $im->annotateImage( $draw, 0, 0, $doc->water_mark_angle, $doc->water_mark_text );
                    }
                    $newWidth = $this->setImageWidth($widthSize, $im->getImageWidth());                        
                    $im->scaleImage($widthSize,$im->getImageHeight(),TRUE);                        
                }
                catch (Exception $e)
                {
                    $im = new Imagick(dirname(Yii::app()->request->scriptFile).DIRECTORY_SEPARATOR."images".DIRECTORY_SEPARATOR."nodisponible.png");
                    $im->setFormat('png');
                    $im->setImageDepth(24); 
                    $size=$im->getImageLength();
                    //$message = $e->getMessage();
                    $outputtype = $im->getFormat();
                    $newWidth = $this->setImageWidth($widthSize, $im->getImageWidth());   
                    $im->scaleImage($widthSize,$im->getImageHeight(),TRUE); 
                }		
                $this->render('view', array('im'=>$im,'f'=>$outputtype,'message'=>$message));
	}
        

        private function setImageWidth($screenWidht, $imageSize)
        {
            return ($screenWidht >= $imageSize)?$imageSize:$screenWidht;
        }
	
}