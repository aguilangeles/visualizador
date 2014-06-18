<?php

/**
 * This is the model class for table "rotulos".
 *
 * The followings are the available columns in table 'rotulos':
 * @property integer $rotulo_id
 * @property string $rotulo_desc
 */
class Rotulos extends CActiveRecord
{
	public $DocsIds;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Rotulos the static model class
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
		return 'rotulos';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rotulo_desc', 'required'),
			array('rotulo_desc', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('rotulo_id, rotulo_desc', 'safe', 'on'=>'search'),
			array('DocsIds','validateGroups'),
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
			'Docs'=>array(self::HAS_MANY, 'Rotulosdoctype', 'rotulo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'rotulo_id' => 'Rótulo ID',
			'rotulo_desc' => 'Nombre',
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

		$criteria->compare('rotulo_id',$this->rotulo_id);
		$criteria->compare('rotulo_desc',$this->rotulo_desc,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	public function afterFind()
	{
		$this->DocsIds = array();
		foreach ($this->Docs as $doc)
		{
		$this->DocsIds = $this->DocsIds + array($doc->doc_type_id=>$doc->doc_type_id);
		}
		parent::afterFind();
	}

	public function validateGroups($attribute,$params)
	{
		$docs = $this->DocsIds;
		$arrayCarats = array();
		if (count($docs)>1)
		{
			foreach ($docs as $doc)
			{
				$arrayCarat = array();
				$doc_type = DocTypes::model()->findByPk($doc);
				foreach ($doc_type->Carats as $carat)
				{
					array_push($arrayCarat, $carat->carat_meta_desc);
				}
				array_push($arrayCarats, $arrayCarat);
			}
			for ($i=0;$i<count($arrayCarats);$i++)
			{
				$i++;
				$diff = array_diff($arrayCarats[0], $arrayCarats[$i]);
				if (count($diff)>0)
				{
					$this->addError('DocsIds','<img src="../images/error.png" width="16" height="16"> Los documentos no tienen las misma metadata de caratula.');
					break;
				}
			}			
		}
		else
		{
			$this->addError('DocsIds','<img src="../images/error.png" width="16" height="16"> Necesita al menos dos documentos, para crear un rótulo.');
		}
	}

}