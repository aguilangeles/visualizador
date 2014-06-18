<?php

/**
 * This is the model class for table "carat_meta".
 *
 * The followings are the available columns in table 'carat_meta':
 * @property integer $carat_meta_id
 * @property string $carat_meta_desc
 * @property string $carat_meta_label
 * @property integer $doc_type_id
 * @property boolean $is_special
 */
class CaratMeta extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return CaratMeta the static model class
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
		return 'carat_meta';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('carat_meta_desc, doc_type_id, carat_meta_label', 'required'),
			array('doc_type_id', 'numerical', 'integerOnly'=>true),
			array('carat_meta_desc, carat_meta_label', 'length', 'max'=>255),
			array('is_special', 'boolean'),
			array('doc_type_id','validateRotuloAsoc'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('carat_meta_id, carat_meta_desc, carat_meta_label, doc_type_id', 'safe', 'on'=>'search'),
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
			'carat_meta_id' => 'ID Carat Meta',
			'carat_meta_desc' => 'Carat Meta Desc',
			'doc_type_id' => 'Doc Type',
			'carat_meta_label' => 'Etiqueta',
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

		$criteria->compare('carat_meta_id',$this->carat_meta_id);
		$criteria->compare('carat_meta_desc',$this->carat_meta_desc,true);
		$criteria->compare('carat_meta_label',$this->carat_meta_label,true);
		$criteria->compare('doc_type_id',$this->doc_type_id);
                $criteria->compare('is_special',$this->is_special);
                
		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	public function validateRotuloAsoc($attribute,$params)
	{
		$c = new CDbCriteria();
		$c->params = array(':doc_type_id'=>(int)$this->doc_type_id);
		$c->condition = 'doc_type_id = :doc_type_id';
		if (count (Rotulosdoctype::model()->findAll($c))!= 0)
		{
			$this->addError('doc_type_id','<img src="../images/error.png" width="16" height="16"> El tipo de documento que seleccionó pertenece a uno o varios rótulos, por favor desvincule el mismo para agregar el metadato de carátula.');
		}
	}
        
        /**
         * Devuelve la etiqueta del tipo de metadata de carátula.
         * @param string $carat_meta_desc
         * @return string
         * @author GDM
         */
        public static function getCMetaLabelByName($carat_meta_desc, $docId, $replace_cmeta = false)
        {
            $c = new CDbCriteria();
            if ($replace_cmeta){
            	$carat_meta_desc = str_replace('CMETA_', '', $carat_meta_desc);
        	}
            $c->params = array(':carat_meta_desc'=>$carat_meta_desc, ':doc_type_id'=>$docId);
            $c->condition = 'carat_meta_desc = :carat_meta_desc AND doc_type_id = :doc_type_id';
            $cMeta = CaratMeta::model()->find($c);
            return ($cMeta != null)?$cMeta->carat_meta_label:'';
        }


        public static function getInputHtml($conditions){
        	$html = '<form id="new_carat_form">';
        	$docType = self::getDocTypeByConditions($conditions);
        	$docTypeId = DocTypes::getDocumentByDocTypeIdByDesc($docType);        	
        	foreach ($conditions as $condition) {
        		if ($condition->field != 'docType' && substr ( $condition->field , 0 , 4 ) != 'OCR_'
        			&& $condition->field != 'c1' 
        			&& $condition->field != 'c2'
        			&& $condition->field != 'c3'
        			&& $condition->field != 'c4'){
        			$html .= '<label>'.self::getCMetaLabelByName($condition->field,$docTypeId,true).'</label>';
        			$html .= '<input type="text" id="'.$condition->field.'" value="'.$condition->value.'"></>';
        		}
        	}
        	return $html.'</form>';
        }

        public static function getDocTypeByConditions($conditions){
        	$result = '';
        	foreach ($conditions as $condition) {
        		if ($condition->field == 'docType'){
        			$result = $condition->value;
        			break;
        		}
        	}
        	return $result;
        }

        public static function updateCMeta($conditions, $new_data){
        	$criteria = Condition::setConditions($conditions);
            $modifier = new EMongoModifier();
            foreach ($new_data as $key => $value)
            {
                $modifier->addModifier($key, 'set', $value);                                              
            }          
            $result = array('message'=>'','success'=> true);         
            $result['distint'] = Idc::model()->distinct('filePath',$criteria);
            $result = self::updateMetaCaratXML($result['distint']['values'],$new_data, $result);
            if($result['success']){
            	$result['status'] = Idc::model()->updateAll($modifier, $criteria);
            	//$result['all'] = Idc::model()->find($criteria);
            	$result['qty'] = Idc::model()->count($criteria);  
            	$result['query'] = json_encode(self::getNewQuery($conditions, $new_data));
            	$result['success'] = (bool)$result['status']['ok'];
            }   
            elseif ($result['message']){
            	$result['message'] = 'Error al intentar modificar datos de carátula.';
            }                   
            return $result;
        }

        protected static function getNewQuery($conditions, $new_data){
        	foreach ($conditions as $condition) {
        		foreach ($new_data as $key => $value) {
        			if ($key == $condition->field){
        				$condition->value = $value;
        				break;
        			}
        			
        		}
        	}
        	return $conditions;
        }

        protected static function updateMetaCaratXML($paths, $new_data, $result =array()){
        	$search = array('/','\\', '|');
        	$xmls= array();
        	$formatted_path = array();
        	for ($i=0; $i < count($paths); $i++) {
        		$formatted_path[$i] = str_replace($search, '/' , $paths[$i])."/Carat.xml";
        		if(file_exists($formatted_path[$i]) ){
        			if (is_writable($formatted_path[$i]) ){
		        		try{

			        		$xmls[$i] = simplexml_load_file($formatted_path[$i]);
			        		if (!$xmls[$i]){
			        			$result['success'] = false;
			        		}  
		        		}
		        		catch(Exception $e){
		        			$result['success'] = false;
		        			$result['message'] = $e->getMessage();
		        			return $result;
		        		}
	        		}
	        		else{
	        			$result['success'] = false;
		        		$result['message'] = 'No tiene permiso de escritura en el archivo '.$formatted_path[$i];
		        		return $result;
	        		}
        		} 
        		else{
        			$result['success'] = false;
	        		$result['message'] = 'No se encontró el archivo '.$formatted_path[$i];
	        		return $result;
        		}     		        	
        	}
        	for ($i=0; $i < count($xmls); $i++)  {    
        		$fields = $xmls[$i]->Caratula->Metadato->children(); 		        	
        		foreach ($fields as $field) {
        			foreach ($new_data as $key => $value) {
        				$c_field = str_replace('CMETA_', '', $key);
        				if (strcasecmp($field->attributes()->Name, $c_field) == 0) {
        					$field->attributes()->Value = $value;
        					$xmls[$i]->asXml($formatted_path[$i]);
        					break;
        				}
        			}
        		}
        	}
        	return $result;
        }
}