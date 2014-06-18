<?php

/**
 * This is the model class for table "rotulos_doctype".
 *
 * The followings are the available columns in table 'rotulos_doctype':
 * @property integer $rotulos_doctype_id
 * @property integer $rotulo_id
 * @property integer $doc_type_id
 */
class Rotulosdoctype extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Rotulosdoctype the static model class
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
		return 'rotulos_doctype';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rotulo_id, doc_type_id', 'required'),
			array('rotulo_id, doc_type_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('rotulos_doctype_id, rotulo_id, doc_type_id', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'rotulos_doctype_id' => 'Rotulos Doctype',
			'rotulo_id' => 'Rotulo',
			'doc_type_id' => 'Doc Type',
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

		$criteria->compare('rotulos_doctype_id',$this->rotulos_doctype_id);
		$criteria->compare('rotulo_id',$this->rotulo_id);
		$criteria->compare('doc_type_id',$this->doc_type_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}