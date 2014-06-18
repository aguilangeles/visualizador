<?php $this->widget('application.extensions.ETooltip.ETooltip', array("selector"=>"#doc-types-form :input:not(:button)",
            "tooltip"=>array(
            "opacity"=>1,
            "position"=>"top center",
            "cancelDefault"=>TRUE,
        ), ));?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'doc-types-form',        
	'enableAjaxValidation'=>true,    
	'clientOptions'=>array(
		'validateOnSubmit'=>true,)
)); ?>
	<fieldset class="form" style="width: auto;padding: 20px 0 20px 0">
		<legend>Complete los datos del documento</legend>
	<?php echo $form->error($model,'doc_type_desc'); ?>
	<?php echo $form->error($model,'doc_type_label'); ?>
	<?php echo $form->error($model,'doc_type_level'); ?>	

	<p>
		<?php echo $form->labelEx($model,'doc_type_desc'); ?>
		<?php echo $form->textField($model,'doc_type_desc',array('size'=>60,'maxlength'=>255,'title'=>'Nombre del documento como figura en el xml. Siempre en mayúscula.')); ?>
	</p>

	<p>
		<?php echo $form->labelEx($model,'doc_type_label'); ?>
		<?php echo $form->textField($model,'doc_type_label',array('size'=>60,'maxlength'=>255,'title'=>'Nombre con el cual se mostraran los documentos.')); ?>
	</p>

	<!--<p>
		<?php /*echo $form->labelEx($model,'path');*/ ?>
		<?php /*echo $form->textField($model,'path',array('size'=>60,'maxlength'=>255,'title'=>'Escriba los paths, separados por una coma. Ej: "D:\,E:\"'));*/ ?>
	</p>-->

	<p>
		<?php echo $form->labelEx($model,'water_mark_text'); ?>
		<?php echo $form->textField($model,'water_mark_text',array('size'=>60,'maxlength'=>100,'title'=>'Si agrega un texto, las imagenes mostraran la marca de agua.')); ?>
	</p>

	<p>
		<?php echo $form->labelEx($model,'water_mark_font_size'); ?>
		<?php echo $form->dropDownList($model,'water_mark_font_size', DocTypes::getFontSizes(),array('title'=>'Tamaño de la fuente, de la marca de agua.')); ?>
	</p>

	<p>
		<?php echo $form->labelEx($model,'water_mark_opacity'); ?>
		<?php echo $form->dropDownList($model,'water_mark_opacity',  DocTypes::getOpacityValues(),array('title'=>'Intensidad de la marca de agua.')); ?>
	</p>

	<p>
		<?php echo $form->labelEx($model,'water_mark_angle'); ?>
		<?php echo $form->dropDownList($model,'water_mark_angle', DocTypes::getAngleDegrees(),array('title'=>'Ángulo con el que se mostrará la marca de agua.')); ?>
	</p>

	<p>
		<?php echo $form->labelEx($model,'doc_type_level'); ?>
		<?php echo $form->dropDownList($model,'doc_type_level',array('1'=>'Nivel 1',
			'2'=>'Nivel 2',
			'3'=>'Nivel 3',
			'4'=>'Nivel 4',),array('title'=>'Nivel del documento.')); ?>
	</p>

	<p>
		<?php echo $form->labelEx($model,'enabled'); ?>
		<?php echo $form->checkBox($model,'enabled',array('title'=>'Si esta seleccionado el documento está habilitado.')); ?>
		<?php echo $form->error($model,'enabled'); ?>
	</p>

	<p>
		<button type="submit" name="Submit">
		<?php echo CHtml::image('../images/add.png',$model->isNewRecord ? 'Crear' : 'Actualizar');?>
		<? echo $model->isNewRecord ? 'Crear' : 'Actualizar';?>
		</button>
		<button type="button" name="Submit" onClick="back()">
		<?php echo CHtml::image('../images/back.png','Volver',array('style'=>'height:16px'));?>
		Volver
		</button>
	</p>
</fieldset>
<?php $this->endWidget(); ?>

<script type="text/javascript">
function back(){
	$("#newDoc").hide();
	$("#doc-types-grid").show();
	$("#buttons").show();
}
</script>