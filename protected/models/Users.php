<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $userId
 * @property string $userName
 * @property string $userPass
 * @property bool $is_admin
 */
class Users extends CActiveRecord
{
	public $GroupsIds;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Users the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('userId, userName, is_admin', 'safe', 'on'=>'search'),
                        array('userName, userPass', 'required','message'=>'<img src="../images/error.png" width="16" height="16">  El campo "{attribute}", no puede estar vacío.'),
			// rememberMe needs to be a boolean
			array('is_admin', 'boolean'),
			array('userName','unique','message'=>'<img src="../images/error.png" width="16" height="16">  Ya existe un usuario con ese nombre.'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'GroupsAsoc'=>array(self::HAS_MANY, 'Usergroups', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'userId' => 'ID de usuario',
			'userName' => 'Nombre de usuario',
			'userPass' => 'Contraseña',
			'is_admin' => 'Es Administrador',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('userId',$this->userId);
		$criteria->compare('userName',$this->userName,true);
		/*$criteria->compare('userPass',$this->userPass,true);*/
                $criteria->compare('is_admin',$this->is_admin,true);    
                                
		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

//	public function getGroups()
//	{
////		foreach ($this->GroupsAsoc as group)
////		{
////			'';
////		}
//	}

	public static  function getAllDocTypes($userId, $level)
	{
		$user = Users::model()->findByPk($userId);
		$docs = array();
		foreach ($user->GroupsAsoc as $group)
			{
				foreach($group->Group->DoctypesAsoc as $docType)
				{
					if ($docType->DocumentType->doc_type_level == $level)
					{
						$docs= $docs + array($docType->DocumentType->doc_type_id=>$docType->DocumentType->doc_type_label);
					}
				}
			}
		return $docs;
	}
	public static  function getRotulosPermission($docs)
	{
		$user = Users::model()->findByPk(Yii::app()->user->id);
		$allowed = true;
		foreach ($docs as $doc)
		{
			if ($allowed)
			{
				$d = DocTypes::model()->findByPk($doc);
				foreach ($user->GroupsAsoc as $group)
				{
					$allowed = false;
					foreach ($d->Groups as $docGroup)
					{
						if ($docGroup->group_id ==$group->group_id)
						{
							$allowed = true;
							break;
						}
					}
				}
			}
			else
			{
				break;
			}
		}
		return $allowed;
	}

	public function afterFind()
	{
		$this->GroupsIds = array();
		foreach ($this->GroupsAsoc as $group)
		{
		$this->GroupsIds = $this->GroupsIds + array($group->group_id=>$group->group_id);
		}
		parent::afterFind();
	}

}