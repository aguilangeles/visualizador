<?php

/**
 * This is the model class for table "usergroups".
 *
 * The followings are the available columns in table 'usergroups':
 * @property integer $usergroup_id
 * @property integer $user_id
 * @property integer $group_id
 */
class Usergroups extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return usergroups the static model class
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
		return 'usergroups';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, group_id', 'required'),
			array('user_id, group_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('usergroup_id, user_id, group_id', 'safe', 'on'=>'search'),
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
			'User'=>array(self::BELONGS_TO, 'Users', 'user_id'),
			'Group'=>array(self::BELONGS_TO, 'Groups', 'group_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'usergroup_id' => 'Usergroup',
			'user_id' => 'User',
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

		$criteria->compare('usergroup_id',$this->usergroup_id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('group_id',$this->group_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}