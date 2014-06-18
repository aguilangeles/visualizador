<?php

/**
 * This is the model class for table "doctypegroups".
 *
 * The followings are the available columns in table 'doctypegroups':
 * @property integer $doctypegroup_id
 * @property integer $doctype_id
 * @property integer $group_id
 */
class Doctypegroups extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Doctypegroups the static model class
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
		return 'doctypegroups';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('doctype_id, group_id', 'required'),
			array('doctype_id, group_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('doctypegroup_id, doctype_id, group_id', 'safe', 'on'=>'search'),
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
			'Group'=>array(self::BELONGS_TO, 'Groups', 'group_id'),
			'DocumentType'=>array(self::BELONGS_TO, 'DocTypes', 'doctype_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'doctypegroup_id' => 'Doctypegroup',
			'doctype_id' => 'Doctype',
			'group_id' => 'Group',
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

		$criteria->compare('doctypegroup_id',$this->doctypegroup_id);
		$criteria->compare('doctype_id',$this->doctype_id);
		$criteria->compare('group_id',$this->group_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}