<?php $this->widget('application.extensions.ETooltip.ETooltip', array("selector"=>"#ocr-meta-form :input:not(:button)",
            "tooltip"=>array(
            "opacity"=>1,
            "position"=>"top center",
            "cancelDefault"=>TRUE,
        ), ));?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ocr-meta-form',
	'enableAjaxValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,)
)); ?>

	<fieldset class="form" style="width: auto;padding: 20px 0 20px 0">
		<legend>Complete los datos de la Metadata</legend>

	<?php //echo $form->errorSummary($model); ?>
	<?php echo $form->error($model,'ocr_meta_desc'); ?>
	<?php echo $form->error($model,'ocr_meta_label'); ?>
	<?php echo $form->error($model,'doc_type_id'); ?>
	<p>
		<?php echo $form->labelEx($model,'ocr_meta_desc'); ?>
		<?php echo $form->textField($model,'ocr_meta_desc',array('size'=>60,'maxlength'=>255,'title'=>'el nombre de la metadata de OCR como figura en el archivo xml.')); ?>
		
	</p>

	<p>
		<?php echo $form->labelEx($model,'ocr_meta_label'); ?>
		<?php echo $form->textField($model,'ocr_meta_label',array('size'=>60,'maxlength'=>255,'title'=>'nombre con el cual se mostrara la metadata OCR.')); ?>
		
	</p>

	<p>
		<?php echo $form->labelEx($model,'doc_type_id'); ?>
		<?php echo CHtml::activeDropDownList($model,'doc_type_id',Chtml::listData(DocTypes::model()->findAll(),'doc_type_id','doc_type_label'),array('title'=>'Elija a que tipo de documento va a pertenecer esta metadata OCR.'));
		////echo $form->textField($model,'doc_type_id'); ?>
		
	</p>

	<p>
		<?php echo $form->labelEx($model,'is_special'); ?>
		<?php echo $form->checkBox($model,'is_special',array('size'=>60,'maxlength'=>255,'title'=>'Habilitar para una busqueda especial')); ?>

	</p>

	<p>
		<button type="submit" name="Submit">
		<?php echo CHtml::image('../images/add.png',$model->isNewRecord ? 'Crear' : 'Actualizar');?>
		<? echo $model->isNewRecord ? 'Crear' : 'Actualizar';?>
		</button>
		<button type="button" name="Submit" onClick="back()">
		<?php echo CHtml::image('../images/back.png','Volver', array ("width" => 16));?>
		Volver
		</button>
	</p>
</fieldset>
<?php $this->endWidget(); ?>
<script type="text/javascript">
function back(){
	$("#newMeta").hide();
	$("#meta-grid").show();
	$("#buttons").show();
}
</script>