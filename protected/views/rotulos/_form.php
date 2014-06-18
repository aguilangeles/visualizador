<?php $this->widget('application.extensions.ETooltip.ETooltip', array("selector"=>"#rotulos-form :input:not(:button)",
            "tooltip"=>array(
            "opacity"=>1,
            "position"=>"top center",
            "cancelDefault"=>TRUE,
        ), ));?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'rotulos-form',
	'enableAjaxValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,)
)); ?>
	<fieldset class="form" style="width: auto;padding: 20px 0 20px 0">
		<legend>Complete los datos del Rótulo</legend>

	<?php //echo $form->errorSummary($model); ?>
	<?php echo $form->error($model,'rotulo_desc'); ?>
		
	<p>
		<?php echo $form->labelEx($model,'rotulo_desc'); ?>
		<?php echo $form->textField($model,'rotulo_desc',array('size'=>60,'maxlength'=>255,'title'=>'nombre del rotulo a crear/editar')); ?>
		
	</p>
	<p style="margin-top: 50px;"><b>ASOCIAR DOCUMENTOS</b></p><hr>
	<?php echo $form->error($model,'DocsIds'); ?>
	<p>
		<?php
			echo CHtml::activeCheckBoxList($model,
					'DocsIds',
					Chtml::listData(DocTypes::model()->getAllDocsByLevel(1),'doc_type_id','doc_type_label'),
                                        array('title'=>'active para asociar este documento al rotulo')
			);
		?>
	</p>
	<p>
		<button type="submit" name="Submit">
		<?php echo CHtml::image('../images/add.png',$model->isNewRecord ? 'Crear' : 'Actualizar');?>
		<? echo $model->isNewRecord ? 'Crear' : 'Actualizar';?>
		</button>
		<button type="button" name="Submit" onClick="back()">
		<?php echo CHtml::image('../images/back.png','Volver');?>
		Volver
		</button>
	</p>
</fieldset>
<?php $this->endWidget(); ?>
<script type="text/javascript">
function back(){
	$("#newDoc").hide();
	$("#rotulos-grid").show();
	$("#buttons").show();
}
</script>