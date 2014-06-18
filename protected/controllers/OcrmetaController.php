<?php

class OcrmetaController extends Controller
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
				'actions'=>array('index','view'),
				'expression'=>'(isset(Yii::app()->user->isAdmin)?Yii::app()->user->isAdmin:false)',
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'expression'=>'(isset(Yii::app()->user->isAdmin)?Yii::app()->user->isAdmin:false)',
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete','EditImageMeta','GetImageMeta'),
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
                        $uppercado=$model->ocr_meta_desc;
                        $model->ocr_meta_desc=strtoupper($uppercado);
                        $verUppercado=$model->ocr_meta_desc;
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
                        $uppercado=$model->ocr_meta_desc;
                        $model->ocr_meta_desc=strtoupper($uppercado);
                        $verUppercado=$model->ocr_meta_desc;
			if($model->save())
				$this->redirect(array('index'));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$this->layout = 'main';
		$model=new OcrMeta('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['OcrMeta']))
			$model->attributes=$_GET['OcrMeta'];

		$this->render('index',array(
			'model'=>$model,
		));
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

	public function actionGetImageMeta(){
		$result = array('success'=>false, 'html'=>'', 'message'=>'Error al intentar de editar la Metadata de imagen.');
		if(isset($_POST['mongo_id'])){
			$id = $_POST['mongo_id'];
			$result = Image::getImageMeta($id,$result);
		}
		echo json_encode($result);
	}

	public function actionEditImageMeta(){
		$result = array('success'=>false,'message'=>'Error al intentar de editar la Metadata de imagen.');
		if(isset($_POST['mongo_id']) && isset($_POST['new_data'])){
			$id = $_POST['mongo_id'];
			$new_data = $_POST['new_data'];
			$result = Image::upsertImageMeta($id, $new_data, $result);
			$result['values'] = $new_data;
		}
		echo json_encode($result);
	}
}
