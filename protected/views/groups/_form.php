<?php $this->widget('application.extensions.ETooltip.ETooltip', array("selector"=>"#groups-form :input:not(:button)",
            "tooltip"=>array(
            "opacity"=>1,
            "position"=>"top center",
            "cancelDefault"=>TRUE,
        ), ));?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'groups-form',
	'enableAjaxValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,)

)); ?>
<fieldset class="form" style="width: auto;padding: 20px 0 20px 0">
		<legend>Complete los datos del grupo</legend>
	<?php //echo $form->errorSummary($model); ?>
	<?php echo $form->error($model,'group_name'); ?>
	<p>
		<?php echo $form->labelEx($model,'group_name'); ?>
		<?php echo $form->textField($model,'group_name',array('size'=>60,'maxlength'=>255, 'title'=>'Nombre del grupo a crear/editar' )); ?>		
	</p>
	<p style="margin-top: 50px;"><b>PERMISOS A DOCUMENTOS</b></p><hr>
	<p>
		<?php
			echo CHtml::activeCheckBoxList($model,
					'DocsIds',
					Chtml::listData(DocTypes::model()->findAll(),'doc_type_id','doc_type_label'),
                                        array('title'=>'active para que el grupo tenga permiso para este tipo de documento')
			);
		?>
	</p>
	<p>
		<button type="submit" name="Submit">
		<?php echo CHtml::image('../images/group_add.png',$model->isNewRecord ? 'Crear' : 'Actualizar');?>
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
	$("#newGroup").hide();
	$("#groups-grid").show();
	$("#buttons").show();
}
</script>