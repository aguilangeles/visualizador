<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of searchtype_1
 *
 * @author aguilangeles@gmail.com
 */
class searchtype
{

    public function searchRadioButton($toggle)
    {
	echo '<div id="searchType" style="width: 260px;float:left;padding: 20px 0 10px 0">
        <div style="float:left;width:100%;padding: 0 0 10px 10px;">' . CHtml::label('Tipo de Busqueda', 'searchType', array('style' => 'text-align: left;')) . '</div>' .
	CHtml::radioButtonList($toggle, '0', array('0' => 'Exacta', '1' => 'Parecida'), array('style' => 'width:50px;float:left;', 'labelOptions' => array('style' => 'width:60px;text-align: left;'), 'separator' => " ")) .
	'</div>';
    }

}
