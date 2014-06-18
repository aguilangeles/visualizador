<?php

class RotulosController extends Controller
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
		$this->layout='edmpty';
		$model=new Rotulos;
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);							
			if(isset($_POST['Rotulos']))
			{
				$model->attributes=$_POST['Rotulos'];
				if($model->save())
				{
					$rotulo_id = $model->primaryKey;
				if(isset($_POST['Rotulos']['DocsIds']))
				{
					$docs = $_POST['Rotulos']['DocsIds'];
					if ($docs != "")
					{
						foreach($docs as $doc)
						{
							$relrotulodoc = new Rotulosdoctype();
							$relrotulodoc->rotulo_id = (int)$rotulo_id;
							$relrotulodoc->doc_type_id = (int)$doc;
							$relrotulodoc->save();
						}
					}
				}
				$this->redirect(array('index'));
			}
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
		$this->layout='edmpty';
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Rotulos']))
		{
			$model->attributes=$_POST['Rotulos'];			
			if($model->save())
			{
				$rotulo_id = $model->primaryKey;
				if(isset($_POST['Rotulos']['DocsIds']))
				{
					$docs = $_POST['Rotulos']['DocsIds'];
					if ($docs != "")
					{
						foreach($docs as $doc)//grabo nuevos registros
						{
							$exists=false;
							foreach($model->Docs as $added)
							{
							if ($added->doc_type_id == $doc)//ya existe
								{
									$exists = true;
									break;
								}
							}
							if (!$exists && $docs != "empty")
							{
								$relrotulodoc = new Rotulosdoctype();
								$relrotulodoc->rotulo_id = (int)$rotulo_id;
								$relrotulodoc->doc_type_id = (int)$doc;
								$relrotulodoc->save();
							}
						}
						foreach($model->Docs as $added)//elimino viejos registros
						{
							$exists=false;
							foreach($docs as $doc)
							{
								if ($added->doc_type_id == $doc)//ya existe
								{
									$exists = true;
									break;
								}
							}
							if (!$exists)
							{
								$added->delete();
							}
						}
					}
				}
				$this->redirect(array('index'));
			}
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
			$model = $this->loadModel($id);
			foreach($model->Docs as $docs)
			{
				$docs->delete();
			}
			$model->delete();
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
		$model=new Rotulos('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Rotulos']))
			$model->attributes=$_GET['Rotulos'];
		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Rotulos('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Rotulos']))
			$model->attributes=$_GET['Rotulos'];

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
		$model=Rotulos::model()->findByPk((int)$id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='rotulos-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
