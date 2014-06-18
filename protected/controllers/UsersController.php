<?php

class UsersController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';

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
				'actions'=>array('changepassword'),
				 'users'=>array('@'),
			),
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
		$model=new Users;
		$modelUserGroup = new Usergroups();
		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
			$model->userPass = md5($model->userPass);
			if($model->save())
			{	$user_id = $model->primaryKey;
				if (isset($_POST['Users']['GroupsIds']))
				{
					$groups = $_POST['Users']['GroupsIds'];
					if ($groups != "")
					{
						foreach($groups as $group)
						{
							$relusergroup = new Usergroups();
							$relusergroup->user_id = (int)$user_id;
							$relusergroup->group_id = (int)$group;
							$relusergroup->save();
						}
					}
				}
				$this->redirect(array('index'));
			}
		}

		$this->render('create',array(
			'model'=>$model,'modelUserGroup'=>$modelUserGroup,
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
		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
			if (isset($_POST['changePassword']))
			{
				$model->userPass = md5($model->userPass);

			}
			if($model->save())
			{	$user_id = $model->primaryKey;
				if (isset($_POST['Users']['GroupsIds']))
				{
					$groups = ($_POST['Users']['GroupsIds']=="")?array("0"=>"empty"):$_POST['Users']['GroupsIds'];
					foreach($groups as $group)//grabo nuevos registros
					{
						$exists=false;
						foreach($model->GroupsAsoc as $added)
						{
							if ($added->group_id == $group)//ya existe
							{
								$exists = true;
								break;
							}
						}
						if (!$exists && $groups != "empty")
						{
							$reldocgroup = new Usergroups();
							$reldocgroup->user_id = (int)$user_id;
							$reldocgroup->group_id = (int)$group;
							$reldocgroup->save();
						}
					}
					foreach($model->GroupsAsoc as $added)//elimino viejos registros
					{
						$exists=false;
						foreach($groups as $group)
						{
							if ($added->group_id == $group)//ya existe
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
			foreach ($model->GroupsAsoc as $groups)
			{
				$groups->delete();
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
//		$dataProvider=new CActiveDataProvider('Users');
//		$this->render('index',array(
//			'dataProvider'=>$dataProvider,
//		));                
		$model=new Users('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Users']))
			$model->attributes=$_GET['Users'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Users('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Users']))
			$model->attributes=$_GET['Users'];

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
		$model=Users::model()->findByPk((int)$id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        /**
         * Cambia la contraseña del usuario 
         */
        public function actionChangepassword()
	{
            $model=new ChangePasswordForm;

            // Uncomment the following line if AJAX validation is needed
            $result = array();
            if(isset($_POST['ajax']) && $_POST['ajax']==='password-form')
                {
                    echo CActiveForm::validate($model);
                    Yii::app()->end();
                }
            if(isset($_POST['ChangePasswordForm']))
            {
                $model->attributes = $_POST['ChangePasswordForm'];
                $user = Users::model()->findByPk((int)Yii::app()->user->id);
                $user->userPass = md5($model->newPassword);
                if($user->save())
                {
                    array_push($result,array('saved'=>true,'message' => 'Se cambió la contraseña exitosamente.'));
                }
                else
                {
                    array_push($result,array('saved'=>false,'message' => 'No se pudo cambiar la contraseña, vuelva a intentarlo.'));
                }
            }

            $this->render('changepassword',array(
                    'model'=>$model, 'result'=>$result,
            ));
	}
        
        
}
