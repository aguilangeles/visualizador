<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Botones de busqueda y de retorno a la pagina anterior.
 *
 * @author aguilangeles@gmail.com
 */
class Botones
{
    public function botonera($search)
    {

	echo '<div id="buttons" style="float:left;width: 260px;padding: 30px 0 0 0;">
		    <div class="login-form" style="float:left;">
			<button style="margin:0 10px 0 0;" type="submit" name="Submit2" onClick="goBack();">';
	echo CHtml::image('../images/back.png', 'Ingresar');
	echo 'Volver
			</button>
		    </div>';
	echo '<div class="login-form" style="float:left;">
			<button style="margin:0 10px 0 0;" type="submit" name="Submit2" onClick="'.$search.';">';
	echo CHtml::image('../images/xmag.png', 'Ingresar');
	echo ' Buscar
			</button>
		    </div>
		</div>';
    }

}
