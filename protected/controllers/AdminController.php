<?php

class AdminController extends Controller
{

	public $layout='main';

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
				'actions'=>array('index','view','Users','ChangePassword','ChangePassword2'),
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

	public function actionIndex()
	{
		$dataProviderUsers = new CActiveDataProvider('Users');
		$this->render('index',array('dataProviderUsers'=>$dataProviderUsers));
	}

	public function actionUsers()
	{
		//$dataProviderUsers = new CActiveDataProvider('Users');
		$this->layout = 'none';
		$this->render('users');
	}

	public function actionChangePassword()
	{
		$this->layout = 'none';
		$model = new ChangePasswordForm;
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='ChangePassword-form-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
		if(isset($_POST['ChangePasswordForm']))
		{
			$model->attributes=$_POST['ChangePasswordForm'];
			//validate user input and redirect to the previous page if valid
			if($model->validate() && $model->changePassword())
				$this->refresh();//$this->redirect('admin/index');
			$model->addError('password', 'Error');
			$this->redirect('index');
		}
		$this->render('changePassword',array('model'=>$model,));
	}
	public function actionChangePassword2()
	{
		$model = new ChangePasswordForm;
		if(isset($_POST['ChangePasswordForm']))
		{
			$model->attributes=$_POST['ChangePasswordForm'];
			//validate user input and redirect to the previous page if valid
			if($model->validate() && $model->changePassword())
			{
				echo 'La contraseña se ha cambiado exitosamente';//$this->redirect('admin/index');
			}
			else
			{
				echo 'Error al cambiar la contrasena';
			}
		}
	}

}