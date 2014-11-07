<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Image
 *
 * @author GDM
 */
class Image {
    
    public $id;
    public $docType;
    public $docSubType;
    public $visibleCarat;
    public $order;
    public $visibleImage;
    public $fileName;
    public $filePath;
    public $face;
    public $idPapel;
    public $idc;
    public $currImageIndex;
    public $prevImageIndex;
    public $nextImageIndex;
    public $reverseImage;
    public $cMeta;
    public $oMeta;
    
   
    
    /**
     * Constructor de image
     * @param type $id
     * @param type $path
     * @param type $prevImageId
     * @param type $nextImageId
     * @param type $cMeta
     * @param type $oMeta 
     */
    public function __construct($id = null,$docType=null,$docSubType=null,$visibleCarat = null,$order=null,$visibleImage=null,$fileName=null,$path=null, $face='Anverso',$idPapel =null,$idc=null, $currImageIndex=null, $prevImageIndex=null,$nextImageIndex=null,$cMeta=null,$oMeta=null)
    {
        $this->id = $id;
        $this->docType = $docType;
        $this->docSubType = $docSubType;
        $this->visibleCarat = $visibleCarat;
        $this->order = $order;
        $this->visibleImage = $visibleImage;
        $this->fileName = $fileName;
        $this->filePath = $path;
        $this->currImageIndex = $currImageIndex;
        $this->prevImageIndex = $prevImageIndex;
        $this->nextImageIndex = $nextImageIndex;
        $this->face = $face;
        $this->idPapel = $idPapel;
        $this->idc = $idc;
        $this->reverseImage = array();
        $this->cMeta = $cMeta;
        $this->oMeta = $oMeta;
    }
    
    
    public static function getImages($id = null, $imagesData, $imageList = null, $limit=50)
    {
        
        $imagesData->reset();
        $prevImage = null;
        $imageList = ($imageList == null)?array('id'=>($id ==null)?uniqid():$id,'lastIndex'=>0,'hasMore'=>false,'qty'=>0,'images'=>array(),'oMeta'=>array()):$imageList;        
        $imageList2 = array('id'=>($id ==null)?uniqid():$id,'lastIndex'=>0,'hasMore'=>false,'qty'=>0,'images'=>array(),'oMeta'=>array());
        $qty = $imagesData->count();
        $index = $imageList['lastIndex'];
        $imagesData->skip($index);
        $imageList['hasMore'] = false;
        $imageCounter = $imageList['qty'];
        $limit = ($limit == 0)? $qty : $imageCounter + $limit;
        foreach ($imagesData as $image)
        { 
            $img = $image;
            $img->id = $image->_id->{'$id'};
            $img->prevImage = array();
            $img->reverseImage = array();
            self::setOmeta($image, $img);
            self::setCmeta($image, $img);
            if ($img->face == 'Anverso')
            {
                if ($imageCounter +1 == $limit +1)
                {
                    $imageList['lastIndex'] = $index;
                    $imageList['hasMore'] = ($index+1 < $qty);
                    return $imageList;
                }
                $img->currImageIndex = $imageCounter;
                $img->prevImageIndex = ($imageCounter == 0)?$imageCounter:$imageCounter-1;
                $img->nextImageIndex = $imageCounter;//($index+1 < $qty)?$imageCounter + 1: $imageCounter;
                if ( count($imageList['images']) > 0 )
                {
                    $imageList['images'][$imageCounter -1]->nextImageIndex = $imageCounter;
                }
                array_push($imageList['images'], $img);                               
                $imageCounter++;
                $imageList['qty'] = $imageCounter;
            }
            else
            {                
                $img->currSubIndex = count($imageList['images'][$imageCounter-1]->reverseImage);
                $img->currImageIndex = $imageList['images'][$imageCounter -1]->currImageIndex;
                $img->prevImageIndex = $imageList['images'][$imageCounter -1]->prevImageIndex;
                $img->nextImageIndex = $imageList['images'][$imageCounter -1]->nextImageIndex;
                array_push($imageList['images'][$imageCounter-1]->reverseImage, $img);
            }                                  
            $imageList['lastIndex'] = $index;
            $index++;
        }   
        
        return $imageList;
    }
    
    public static function getImages2($id = null, $imagesData, $imageList = null, $limit=50)
    {
        
        $imagesData->reset();
        $prevImage = null;
        $imageList = ($imageList == null)?array('id'=>($id ==null)?uniqid():$id,'lastIndex'=>0,'hasMore'=>false,'qty'=>0,'images'=>array(),'oMeta'=>array()):$imageList;        
        $imageList2 = array('id'=>($id ==null)?uniqid():$id,'lastIndex'=>0,'hasMore'=>false,'qty'=>0,'images'=>array(),'oMeta'=>array());
        $qty = $imagesData->count();
        $index = $imageList['lastIndex'];
        $imagesData->skip($index);
        $imageList['hasMore'] = false;
        $imageCounter = $imageList['qty'];
        $limit = ($limit == 0)? $qty : $imageCounter + $limit;
        foreach ($imagesData as $image)
        { 
            $img = $image;
            $img->id = $image->_id->{'$id'};
            $img->prevImage = array();
            $img->reverseImage = array();
            self::setOmeta($image, $img);
            self::setCmeta($image, $img);
            if ($img->face == 'Anverso')
            {
                $img->currImageIndex = $imageCounter;
                $img->prevImageIndex = ($imageCounter == 0)?$imageCounter:$imageCounter-1;
                $img->nextImageIndex = $imageCounter;//($index+1 < $qty)?$imageCounter + 1: $imageCounter;
                if ( count($imageList['images']) > 0 )
                {
                    $imageList['images'][$imageCounter -1]->nextImageIndex = $imageCounter;
                }
                array_push($imageList['images'], $img);                               
                $imageCounter++;
                $imageList['qty'] = $imageCounter;
            }
            else
            {                
                $img->currSubIndex = count($imageList['images'][$imageCounter-1]->reverseImage);
                $img->currImageIndex = $imageList['images'][$imageCounter -1]->currImageIndex;
                $img->prevImageIndex = $imageList['images'][$imageCounter -1]->prevImageIndex;
                $img->nextImageIndex = $imageList['images'][$imageCounter -1]->nextImageIndex;
                array_push($imageList['images'][$imageCounter-1]->reverseImage, $img);
            }                                  
            $imageList['lastIndex'] = $index;
            $index++;
        }   
        
        return $imageList;
    }
    
    public static function getImagesoRG($id = null, $imagesData, $imageList = null, $limit=50)
    {
        $imageList = ($imageList == null)?array('id'=>($id ==null)?uniqid():$id,'lastIndex'=>0,'hasMore'=>false,'qty'=>0,'images'=>array()):$imageList;
        $qty = count($imagesData);
        $imageList['hasMore'] = false;
        $imageCounter = $imageList['qty'];
        $limit = ($limit == 0)? $qty : $imageCounter + $limit;
        for ($x = $imageList['lastIndex']; $x< $qty ; $x++)
        {
            if (is_object($imagesData[$x]))
            {                                
                $img = new Image();                
                $img->id = $imagesData[$x]->{'$id'};$x++;
                $img->docType = $imagesData[$x];$x++;
                $img->docSubType = $imagesData[$x];$x++;
                $img->visibleCarat = $imagesData[$x];$x++;
                $img->order = $imagesData[$x];$x++;
                $img->visibleImage = $imagesData[$x];$x++;
                $img->fileName = $imagesData[$x];$x++;
                $img->filePath = $imagesData[$x];$x++;
                $img->face = $imagesData[$x];$x++;
                $img->idPapel = $imagesData[$x];$x++;
                $img->idc = $imagesData[$x];$x++;//11
                $x = self::setCmeta($imagesData, $x, $img);
                $x = self::setOmeta($imagesData, $x, $img);                                                
                if ($img->face == 'Reverso')
                {       
                    $img->currImageIndex = count($imageList['images'][$imageCounter -1]->reverseImage);
                    $img->prevImageIndex = $imageList['images'][$imageCounter -1]->prevImageIndex;
                    $img->nextImageIndex = $imageList['images'][$imageCounter -1]->nextImageIndex;
                    array_push($imageList['images'][$imageCounter -1]->reverseImage,$img);
                }
                else
                {
                    $img->currImageIndex = $imageCounter;
                    $img->prevImageIndex = ($imageCounter == 0)?$imageCounter:$imageCounter-1;
                    $img->nextImageIndex = ($x+1 < $qty)?$imageCounter + 1: $imageCounter;
                    array_push($imageList['images'],$img);
                    $imageCounter++;
                    $imageList['qty'] = $imageCounter;
                }                   
                
            }   
            if ($imageCounter == $limit)
            {
                $imageList['lastIndex'] = $x+1;
                $imageList['hasMore'] = ($x+1 < $qty);
                return $imageList;
            }
        }        
        return $imageList;
    }
    
    
    public static function writeImageData($imagesData)
    {
        $result = array(); 
        for($i= 0; $i<count($imagesData);$i++)
        {
            $id = uniqid();
            $html = "<div id='imageData".$id."' style='display:none;'>";
            $html = $html.json_encode($imagesData[$i]['images']);
            $html = $html . '</div>';
            array_push($result, array($id => $html));
        }
        return $result;
    }


    
    /**
     * Setea los valores de ocer y devuelve el indice de donde esta parado.
     * @param type $imagesData
     * @param type $x
     * @param type $img 
     */
    private static function setOmeta($image, $img)
    {
        $img->oMeta = array();
        $doc = DocTypes::getDocumentByDocTypeDesc($img->docType);
        $fields = DocTypes::getMetaOcrs($doc->doc_type_id);
        foreach ($fields as $field)
        {
            try
            {
                $fieldLabel = OcrMeta::model()->getOCRLabel($doc->doc_type_desc, $field);
                $descName = "OCR_".$field;
                $fieldValue = array($fieldLabel=>$image->$descName);                            
            }
            catch (Exception $e)
            {
                $fieldValue = array($fieldLabel=>''); 
            }
            array_push($img->oMeta, $fieldValue);
        }
    }
    
    //todo revisar porque viene null
    /**
     * Setea los valores de carat y devuelve el indice de donde esta parado.
     * @param type $imagesData
     * @param type $x
     * @param type $img 
     */
    private static function setCmeta($image, $img)
    {                
        $img->cMeta = array();
        $doc = DocTypes::getDocumentByDocTypeDesc($img->docType);
        $fields = DocTypes::getMetaCarats($doc->doc_type_id);
        foreach ($fields as $field)
        {
            $fieldLabel = CaratMeta::model()->getCMetaLabelByName($field, $doc->doc_type_id);
            $descName = "CMETA_".$field;
            $fieldValue = array($fieldLabel=>$image->$descName);            
            array_push($img->cMeta, $fieldValue);
        }
    }
    
    /**
     * Devuelve un string con los datos de ocr de la imagen
     * @param type $image
     * @return type 
     */
    public static function getImageOcrMeta($image)
    {
        $string = '';
        if ($image->oMeta == null)
        {
            return $string;
        }                
        foreach($image->oMeta as $ocr)
        {
            $ocr = get_object_vars($ocr);
            $string = $string.key($ocr).": <b>".$ocr[key($ocr)]. "</b> | ";
        }
        return substr($string, 0,  (strlen($string)-3));
    }
    
    /**
     * Devuelve un string con los datos de Carat de la imagen
     * @param type $image
     * @return type 
     */
    public static function getImageCaratMeta($image)
    {
        $string = '';
        foreach($image->cMeta as $carat)
        {
            $carat = get_object_vars($carat);
            $string = $string.key($carat).": <b>".$carat[key($carat)]. "</b> | ";
        }
        return substr($string, 0,  (strlen($string)-3));
    }

    public static function getImageMeta($id, $result = array()){
        $_id = new MongoId($id);
        $record = Idc::model()->findByPk($_id);
        if ($record){
            $result['html'] = '<form name="new_meta_form" id="new_meta_form">';
            $doc_type = DocTypes::getDocumentByDocTypeDesc($record->docType);
            foreach ($doc_type->OCRs as $ocr) {
                $result['html'] .= '<label>'.$ocr->ocr_meta_label.'</label>';
                $val = '';
                $id = 'OCR_'.$ocr->ocr_meta_desc;
                foreach ($record->softAttributes as $attribute=>$value) {
                    if($attribute == 'OCR_'.$ocr->ocr_meta_desc){
                        $val = $value;
                        break;
                    }
                }
                $result['html'] .= '<input type"text" name="'.$id.'" id="'.$id.'" value="'.$val.'"></>';
            }
        }
        $result['html'] .= '</form>';
        $image_path = str_replace(array('\\','|'), '/', $record->filePath.'/Imagenes/'.$record->fileName);      
        $doc= $record->docType;
        $doc_subType = $record->docSubtipo;
        $image_src =  Yii::app()->request->hostInfo.'/showimage/view?widthSize=auto&doc='.$doc.'&subdoc='.$doc_subType.'&path='.urlencode($image_path);
        $result['html'].= '<image src="'.$image_src.'"/>';
        return $result;
    }

    public static function upsertImageMeta($id, $new_data, $result = array()){
        $_id = new MongoId($id);       
        $record = Idc::model()->findByPk($_id);
        $result = self::updateImageMetaXML($record, $new_data, $result);
        foreach ($new_data as $key => $value)
        {
            $record->softAttributes[$key] = $value;                                                        
        }   
        $result['success'] = $record->save(false);
        return $result;
    }

      protected static function updateImageMetaXML($record, $new_data, $result =array()){
            $search = array('/','\\', '|');
            $formatted_path = str_replace($search, '/' , $record->filePath)."/Meta.xml";
            if(file_exists($formatted_path) ){
                if (is_writable($formatted_path) ){
                        try{
                            $xml = simplexml_load_file($formatted_path);
                            if (!$xml){
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
                        $result['message'] = 'No tiene permiso de escritura en el archivo '.$formatted_path;
                        return $result;
                    }
                } 
                else{
                    $result['success'] = false;
                    $result['message'] = 'No se encontró el archivo '.$formatted_path;
                    return $result;
                }                                   
                $found = false;
                $metas = $xml->children();                                
                foreach ($metas as $meta) {
                    if (!$found && $meta->getName() == 'Meta'){
                        $fields = $meta->Image->children();                        
                        foreach ($fields as $field) {
                            if ($field->attributes()->Name == 'Id Imagen'){
                                if ($record->fileName == $field->attributes()->Value){
                                    $found = true;                         
                                $fields = self::updateMetaXml($meta, $new_data, $result);                                                           
                                }
                                else{
                                    break;
                                }
                            }      
                        }
                    }                                        
                }
                if (!$found){
                    $xml = self::createMetaXml($xml, $record, $new_data, $result);
                    $dom = new DOMDocument("1.0");
                    $dom->preserveWhiteSpace = false;
                    $dom->formatOutput = true;
                    $dom->loadXML($xml->asXML());
                    $formatted_xml = $dom->saveXML();
                    $xml = simplexml_load_string ($formatted_xml);
                }
                $xml->asXml($formatted_path);
            return $result;
        }

        protected static function createMetaXml($xml, $record, $new_data, $result=array()){
            $meta = $xml->addChild('Meta');
            $meta->addChild('IdIDC',$record->IDC);
            $meta->addChild('CreationDate',date('Y-m-d h:i:s', $record->Creation_date->sec));
            $meta->addChild('Status','WEB');
            $image = $meta->addChild('Image');
            $campo = $image->addChild('Campo');
            $campo->addAttribute('Name','Id Imagen');
            $campo->addAttribute('Value',$record->fileName);
            foreach ($new_data as $key => $value) {
                $c_field = str_replace('OCR_', '', $key);
                $campo = $image->addChild('Campo');
                $campo->addAttribute('Name',$c_field);
                $campo->addAttribute('Value',$value);
                $campo->addAttribute('Status','Invalid');
            }
            return $xml;
        }

        protected static function updateMetaXml($meta, $new_data, $result=array()){
            $fields = $meta->Image->children();
            foreach ($fields as $field) {
                foreach ($new_data as $key => $value) {
                    $c_field = str_replace('OCR_', '', $key);
                    if (strcasecmp($field->attributes()->Name, $c_field) == 0) {
                        $field->attributes()->Value = $value;                        
                    }
                }
            }
            return $fields;
        }
}

?>
