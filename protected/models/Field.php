<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Fields
 * @property string $name
 * @property string $label
 * @author GDM
 */
 class Field {
            
    public $name;
    public $label;
    public $prefix;
    
    
    /**
     * Constructor de Field
     * @param string $name
     * @param string $label
     * @param string $prefix 
     * @param DocTypes $doc 
     */
    public function __construct($name = '', $label = '', $prefix = '', $doc = '')
    {
        $this->name = $name;
        $this->label = $label;
        $this->prefix = $prefix;
        $this->doc = $doc;
    }
    
    public static function getField($name, $dataType, $docName)
    {
        $document = DocTypes::getDocumentByDocTypeDesc($docName);
        switch ($dataType)
        {
            case 'OCR': return self::findOcrField($name, $document);
            default: return self::findCmetaField($name,$document);
        }
    }
    
    private static function findOcrField($name, $document)
    {
        return new Field($name,  OcrMeta::model()->getOCRLabelByName($name, $document->doc_type_id),'OCR_', $document->doc_type_desc);
    }
    
    private static function findCmetaField($name, $document)
    {
        return new Field($name,  CaratMeta::model()->getCMetaLabelByName($name, $document->doc_type_id),'CMETA_', $document->doc_type_desc);
    }
}

?>
