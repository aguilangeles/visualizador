<?php

/**
 * This is the model class for table "ocr_meta".
 *
 * The followings are the available columns in table 'ocr_meta':
 * @property integer $ocr_meta_id
 * @property string $ocr_meta_desc
 * @property string $ocr_meta_label
 * @property integer $doc_type_id
 */
class OcrMeta extends CActiveRecord
{
		public $documento;
	/**
	 * Returns the static model of the specified AR class.
	 * @return OcrMeta the static model class
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
		return 'ocr_meta';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ocr_meta_desc, doc_type_id, ocr_meta_label', 'required'),
			array('doc_type_id', 'numerical', 'integerOnly'=>true),
			array('ocr_meta_desc', 'length', 'max'=>255),
			array('is_special', 'boolean'),
			array('documento', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ocr_meta_id, ocr_meta_desc, doc_type_id', 'safe', 'on'=>'search'),
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
			'Doc'=>array(self::BELONGS_TO, 'DocTypes', 'doc_type_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ocr_meta_id' => 'ID de Ocr Meta',
			'ocr_meta_desc' => 'Ocr Meta Desc',
			'doc_type_id' => 'Doc Type',
			'ocr_meta_label'=>'Etiqueta',
			'is_special'=>'Especial',
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
		$criteria->with=array('Doc',);
		$criteria->addSearchCondition('Doc.doc_type_label', $this->documento);
		

		$criteria->compare('ocr_meta_id',$this->ocr_meta_id);
		$criteria->compare('ocr_meta_desc',$this->ocr_meta_desc,true);
		$criteria->compare('ocr_meta_label',$this->ocr_meta_label,true);
		$criteria->compare('doc_type_id',$this->doc_type_id);
                $criteria->compare('is_special',$this->is_special);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
        
        public static function getOCRLabel($docTypeDesc,$field)
        {
            $criteria = new CDbCriteria();
            $criteria->condition = 'doc_type_desc = :doc_type_desc';
            $criteria->params = array(':doc_type_desc'=>$docTypeDesc);
            $doc = DocTypes::model()->find($criteria);
            foreach ($doc->OCRs as $ocr)
            {
                if ($field == $ocr->ocr_meta_desc)
                    return $ocr->ocr_meta_label;
            }
        }
        
        public static function getOCRLabelByName($ocrDesc, $docId=null)
        {        	
        	$param = array();        	
        	$param[':ocr_meta_desc'] = $ocrDesc;
        	$_condition = 'ocr_meta_desc = :ocr_meta_desc';
        	if ($docId != null){
        		$param[':doc_type_id'] = $docId;
        		$_condition .= ' AND doc_type_id = :doc_type_id';
        	}
            $c = new CDbCriteria();
            $c->params = $param;//array(':ocr_meta_desc'=>$ocrDesc, ':doc_type_id'=>$docId);
            $c->condition = $_condition;//'ocr_meta_desc = :ocr_meta_desc AND doc_type_id = :doc_type_id';
            return OcrMeta::model()->find($c)->ocr_meta_label;
        }

        public static function getOCRNameByLabel($ocrLabel, $docId=null)
        {        	
        	$param = array();        	
        	$param[':ocr_meta_label'] = $ocrLabel;
        	$_condition = 'ocr_meta_label = :ocr_meta_label';
        	if ($docId != null){
        		$param[':doc_type_id'] = $docId;
        		$_condition .= ' AND doc_type_id = :doc_type_id';
        	}
            $c = new CDbCriteria();
            $c->params = $param;
            $c->condition = $_condition;
            return OcrMeta::model()->find($c)->ocr_meta_desc;
        }
     
}