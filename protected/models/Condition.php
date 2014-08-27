<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Condition
 *
 * @author GDM
 */
class Condition {
    
    public $field;
    public $operator;
    public $value;
    
    public function __construct($field, $operator, $value)
    {
        $this->field = $field;
        $this->operator = $operator;
        $this->value = $value;
    }
    
    public static function setConditions($conditions, $criteria = null)
    {
        $criteria = ($criteria == null)? new EMongoCriteria : $criteria;
        foreach($conditions as $condition)
        {
            if ($condition->operator == 'regex')
            {
                $query = new MongoRegex('/'.$condition->value.'/i');
                $criteria->addCond($condition->field, '==', $query);
            }
            else
            {
                $criteria->addCond($condition->field, $condition->operator, $condition->value);
            }
        }
        $criteria->setSort(array('idPapel'=>EMongoCriteria::SORT_ASC));
        return $criteria;
    }

    public static function noImageMetaData($conditions){
        foreach($conditions as $condition)
        {
            if (substr($condition->field,0,4) == 'OCR_')
            {
                return false;
            }
        }
        return true;
    }
}


