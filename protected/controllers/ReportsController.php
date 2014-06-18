<?php

class ReportsController extends Controller
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view','idcSearch'),
				'expression'=>'(isset(Yii::app()->user->isAdmin)?Yii::app()->user->isAdmin:false)',
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

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new OcrMeta;
		$this->layout='edmpty';
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['OcrMeta']))
		{
			$model->attributes=$_POST['OcrMeta'];
			if($model->save())
				$this->redirect(array('index'));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$this->layout='edmpty';
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['OcrMeta']))
		{
			$model->attributes=$_POST['OcrMeta'];
			if($model->save())
				$this->redirect(array('index'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionIdcSearch()
	{
            if (isset ($_POST['idc']))
            {
                
                $idcName = $_POST['idc'];
                $c = new EMongoCriteria();
                $c->addCond('IDC', '==', $idcName);
		$idcs = new Idc($c);
		$results = $idcs->findAll($c);
		$group = Idc::getGroup($c);
                $values =  $group["retval"];
                $criteria=new CDbCriteria;
                $content = 'Imágenes contenidas en el IDC: '.$group["count"].'<br><br>';
		$criteria->compare('IDC',$idcName);

		$dp = new CActiveDataProvider('Users');
                foreach ($values as $value)
                {
                    $content = $content.'<br>';
                    for($x=6;$x<count($value['images']);$x=$x+8)
                    {
                        $content = $content.'<br>';
                        $dp->data;
                        $content = $content.$value['images'][$x].'&nbsp&nbsp&nbsp&nbsp';
                        $x=$x+8;
                        if ($x < count($value['images']))
                        {
                            $content = $content.$value['images'][$x].'&nbsp&nbsp&nbsp&nbsp';
                            $x=$x+8;
                            if ($x < count($value['images']))
                            {
                                $content = $content.$value['images'][$x].'&nbsp&nbsp&nbsp&nbsp';
                                $x=$x+8;
                                if ($x < count($value['images']))
                                {
                                    $content = $content.$value['images'][$x].'&nbsp&nbsp&nbsp&nbsp';
                                    $x=$x+8;
                                    if ($x < count($value['images']))
                                        $content = $content.$value['images'][$x];
                                }
                            }
                        }
                    }
                }
                echo $content;
            }
        }


	public function actionIndex()
	{
            $this->layout = 'main';
            $this->render('index');
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new OcrMeta('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['OcrMeta']))
			$model->attributes=$_GET['OcrMeta'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=OcrMeta::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
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
