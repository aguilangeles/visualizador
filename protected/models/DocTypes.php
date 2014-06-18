<?php

/**
 * This is the model class for table "doc_types".
 *
 * The followings are the available columns in table 'doc_types':
 * @property integer $doc_type_id
 * @property string $doc_type_desc
 * @property string $doc_type_label
 * @property integer $doc_type_level
 * @property string $water_mark_text
 * @property string $water_mark_font_size
 * @property string $water_mark_opacity
 * @property string $water_mark_angle
 * @property integer $enabled
 */
class DocTypes extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return DocTypes the static model class
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
		return 'doc_types';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('doc_type_desc, doc_type_label', 'required','message'=>'<img src="../images/error.png" width="16" height="16">  El campo "{attribute}", no puede estar vacío.'),
			array('doc_type_level, enabled, water_mark_font_size, water_mark_angle', 'numerical', 'integerOnly'=>true),
			array('water_mark_opacity', 'numerical', 'integerOnly'=>false),
			array('doc_type_desc,doc_type_label', 'length', 'max'=>255),
			array('water_mark_text', 'length', 'max'=>100),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('doc_type_id, doc_type_desc, doc_type_level, water_mark_text, water_mark_angle, enabled,', 'safe', 'on'=>'search'),
			array('doc_type_desc','unique','message'=>'<img src="../images/error.png" width="16" height="16">  Ya existe una descripción con ese nombre.'),
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
			'Carats'=>array(self::HAS_MANY, 'CaratMeta', 'doc_type_id'),
			'OCRs'=>array(self::HAS_MANY, 'OcrMeta', 'doc_type_id'),
			'Groups'=>array(self::HAS_MANY, 'Doctypegroups', 'doctype_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'doc_type_id' => 'ID de Documento',
			'doc_type_desc' => 'Descripción',
			'doc_type_label' => 'Etiqueta',
			'doc_type_level' => 'Nivel',			
			'water_mark_text'=>'Marca de Agua',
			'water_mark_font_size'=>'Tamaño (Fuente)',
			'water_mark_opacity'=> 'Opacidad',
			'water_mark_angle'=>'Ángulo',
			'enabled' => 'Habilitado',
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
                                
		$criteria->compare('doc_type_id',$this->doc_type_id);
		$criteria->compare('doc_type_desc',$this->doc_type_desc,true);
                $criteria->compare('doc_type_label',$this->doc_type_label,true);
		$criteria->compare('doc_type_level',$this->doc_type_level);
		$criteria->compare('enabled',$this->enabled);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	public static function getAllDocsByLevel($level)
	{
		$c = new CDbCriteria();
		$c->condition = 'doc_type_level = :level';
		$c->params = array(':level'=>(int)$level);
		return DocTypes::model()->findAll($c);
	}

	public static function getAngleDegrees()
	{
		$beginDegree = 1;
		$lastDegree = 360;
		$degrees =array(0=>'Seleccione un angulo');
		for ($degree = $beginDegree; $degree <= $lastDegree; $degree++)
		{
			$degrees =$degrees+ array($degree=>$degree.'°');
		}
		return $degrees;
	}

	public static function getOpacityValues()
	{
		$beginOpacityValue = 1;
		$lastOpacityValue = 10;
		$opacityValues =array(0=>'Seleccione la opacidad');
		for ($opacityValue = $beginOpacityValue; $opacityValue <= $lastOpacityValue; $opacityValue++)
		{
			$opacityValues =$opacityValues+ array($opacityValue=>(float)$opacityValue/10);
		}
		return $opacityValues;
	}

	public static function getFontSizes()
	{
		$beginValue = 12;
		$lastValue = 100;
		$values =array(10=>'Seleccione el tamaño de fuente');
		for ($value = $beginValue; $value <= $lastValue; $value++)
		{
			$values = $values + array($value=>$value);
		}
		return $values;
	}

        /**
         * Devuelve el objeto DocTypes que corresponde a la descripcion del mismo.
         * @param string $docType
         * @return DocTypes::Object
         * @author GDM
         */
        public static function getDocumentByDocTypeDesc($docType)
        {
            $c = new CDbCriteria();
            $c->params = array(':doc_type_desc'=>$docType);
            $c->condition = 'doc_type_desc = :doc_type_desc';
            return DocTypes::model()->find($c);
        }
        

        /**
         * Devuelve el objeto DocTypeId que corresponde a la descripcion del mismo.
         * @param string $docType
         * @return DocTypes::Object
         * @author GDM
         */
        public static function getDocumentByDocTypeIdByDesc($docType)
        {
            $c = new CDbCriteria();
            $c->params = array(':doc_type_desc'=>$docType);
            $c->condition = 'doc_type_desc = :doc_type_desc';
            return DocTypes::model()->find($c)->doc_type_id;
        }

        /**
         * Devuelve la etiqueta del tipo de documento.
         * @param string $docTypeName
         * @return string
         * @author GDM
         */
        public static function getDocumentDesc($docTypeName)
        {
            $c = new CDbCriteria();
            $c->params = array(':doc_type_desc'=>$docTypeName);
            $c->condition = 'doc_type_desc = :doc_type_desc';
            return DocTypes::model()->find($c)->doc_type_label;
        }
        
        
        /**
         * Devuelve las caratulas.
         * @param string $docTypeName
         * @return string
         * @author GDM
         */
        public static function getMetaCarats($docTypeId)
        {
            $carats = array();
            $doc = DocTypes::model()->findByPk($docTypeId);
            foreach ($doc->Carats as $carat)
            {
                array_push($carats,$carat->carat_meta_desc) ;
            }
            return $carats;
        }
        
        /**
         * Devuelve los Ocrs.
         * @param string $docTypeName
         * @return string
         * @author GDM
         */
        public static function getMetaOcrs($docTypeId)
        {
            $ocrs = array();
            $doc = DocTypes::model()->findByPk($docTypeId);
            foreach ($doc->OCRs as $ocr)
            {
                array_push($ocrs,$ocr->ocr_meta_desc) ;
            }
            return $ocrs;
        }
}