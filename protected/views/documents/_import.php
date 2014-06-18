<?php $this->widget('application.extensions.ETooltip.ETooltip', array("selector"=>"#import-form :input:not(:button)",
            "tooltip"=>array(
            "opacity"=>1,
            "position"=>"top center",
            "cancelDefault"=>TRUE,
        ), ));?>
<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'import-form',
		'enableAjaxValidation'=>true,
		'clientOptions'=>array('validateOnSubmit'=>true,)
		)); 
?>

	<fieldset class="form" style="width: auto;padding: 20px 0 20px 0">
			<legend>Importar</legend>

		<div style="float:left">
			<?php echo CHtml::label('Ruta del IDC', 'idcPath'); ?>
	        <?php echo CHtml::textField('idcPath','',array('id'=>'idcPath','title'=>'Ingrese la ruta del IDC'));?>
		</div>

		<button type="button" name="Submit" onClick="importIDC()" style="float:left">
			Importar
		</button>
		
		<img id="imgLoadingImport" src="../images/loading.gif" alt="cargando" style="display:none;clear:both">
		<div style="clear:both;margin-bottom:10px;"></div>
		<div id="importCont" class="resultCont">
		    <textarea id="importResult" class="result">
		    </textarea>
	    </div>

	    <a href='importImagesIndex' class="link" style="margin-left:70px;">Importar Imagenes</a>
	</fieldset>
<?php $this->endWidget(); ?>
