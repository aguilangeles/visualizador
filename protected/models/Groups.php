<?php

/**
 * This is the model class for table "groups".
 *
 * The followings are the available columns in table 'groups':
 * @property integer $group_id
 * @property string $group_name
 */
class Groups extends CActiveRecord
{
	public $DocsIds;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Groups the static model class
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
		return 'groups';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('group_name', 'required','message'=>'<img src="../images/error.png" width="16" height="16">  El campo "{attribute}", no puede estar vacío.'),
			array('group_name', 'unique','message'=>'<img src="../images/error.png" width="16" height="16">  Ya existe un grupo con ese nombre.'),
			array('group_id', 'numerical', 'integerOnly'=>true),
			array('group_name', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('group_id, group_name', 'safe', 'on'=>'search'),
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
			'DoctypesAsoc'=>array(self::HAS_MANY, 'Doctypegroups', 'group_id'),
			'UsersAsoc'=>array(self::HAS_MANY, 'Usergroups', 'group_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'group_id' => 'ID de Grupo',
			'group_name' => 'Nombre',
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

		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('group_name',$this->group_name,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	public function afterFind()
	{
		$this->DocsIds = array();
		foreach ($this->DoctypesAsoc as $doc)
		{
		$this->DocsIds = $this->DocsIds + array($doc->doctype_id=>$doc->doctype_id);
		}
		parent::afterFind();
	}
}