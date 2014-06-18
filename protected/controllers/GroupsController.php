<?php

class GroupsController extends Controller
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
		$this->layout = 'edpty';
		$model=new Groups;
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);
		
		if(isset($_POST['Groups']))
		{
			$model->attributes=$_POST['Groups'];			
			if($model->save())
			{	$group_id = $model->primaryKey;
				if (isset($_POST['Groups']['DocsIds']))
				{
					$docs = $_POST['Groups']['DocsIds'];
					if ($docs != "")
					{
						foreach($docs as $doctype)
						{
							$reldocgroup = new Doctypegroups();
							$reldocgroup->doctype_id = (int)$doctype;
							$reldocgroup->group_id = (int)$group_id;
							$reldocgroup->save();
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
		$this->layout = 'edpty';
		$model=$this->loadModel($id);
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Groups']))
		{
			$model->attributes=$_POST['Groups'];
			if($model->save())
			{	$group_id = $model->primaryKey;
				if (isset($_POST['Groups']['DocsIds']))
				{					
					$docs = ($_POST['Groups']['DocsIds']=="")?array("0"=>"empty"):$_POST['Groups']['DocsIds'];

					foreach($docs as $doctype)//grabo nuevos registros
					{
						$exists=false;
						foreach($model->DoctypesAsoc as $added)
						{
							if ($added->doctype_id == $doctype)//ya existe
							{
								$exists = true;
								break;
							}
						}
						if (!$exists && $doctype != "empty")
						{
							$reldocgroup = new Doctypegroups();
							$reldocgroup->doctype_id = (int)$doctype;
							$reldocgroup->group_id = (int)$group_id;
							$reldocgroup->save();
						}
					}
					foreach($model->DoctypesAsoc as $added)//elimino viejos registros
					{
						$exists=false;
						foreach($docs as $doctype)
						{
							if ($added->doctype_id == $doctype)//ya existe
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
			foreach ($model->DoctypesAsoc as $docs)
			{
				$docs->delete();
			}
			foreach ($model->UsersAsoc as $users)
			{
				$users->delete();
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
		//$dataProvider=new CActiveDataProvider('Groups');
		//$this->render('index',array(
		//	'dataProvider'=>$dataProvider,
		//));
		$model=new Groups('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Groups']))
			$model->attributes=$_GET['Groups'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Groups('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Groups']))
			$model->attributes=$_GET['Groups'];

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
		$model=Groups::model()->findByPk((int)$id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='groups-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
