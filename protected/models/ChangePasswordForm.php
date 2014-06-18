<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class ChangePasswordForm extends CFormModel
{
	public $password;
	public $newPassword;
	public $newPassword2;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('newPassword, password, newPassword2', 'required','message'=>'Complete el campo "{attribute}", no puede estar vacío.'),
			array('newPassword2', 'compare', 'compareAttribute'=>'newPassword','message'=>'Las contraseñas deben coincidir'),
			// password needs to be authenticated
			//array('password', 'authenticate','mesage'=>'Contraseña anerior no válida.'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'password'=>'Contraseña Anterior',
			'newPassword'=>'Contraseña Nueva',
			'newPassword2'=>'Repita Contraseña',
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity(Yii::app()->user->userName,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password','Contraseña anterior incorrecta.');
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function changePassword()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$user = Users::model()->findByPk(Yii::app()->user->id);
			$user->userPass = md5($this->newPassword);
			if ($user->save())
			return true;
		}
		else
			return false;
	}
}
