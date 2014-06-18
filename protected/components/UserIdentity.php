<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	private $_isAdmin;
	private $_userName;
	
    public function authenticate()
    {
        $record=Users::model()->findByAttributes(array('userName'=>$this->username));
        if($record===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if($record->userPass!==md5($this->password))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
        {
            $this->_id=$record->userId;
			$this->_isAdmin = $record->is_admin;
			$this->_userName = $record->userName;
			$this->setState('id', $this->_id);
            $this->setState('isAdmin', $record->is_admin);
			$this->setState('userName',$this->_userName);
            $this->errorCode=self::ERROR_NONE;
        }
        return !$this->errorCode;
    }

    public function getId()
    {
        return $this->_id;
    }
}