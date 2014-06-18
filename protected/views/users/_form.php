<?php $this->widget('application.extensions.ETooltip.ETooltip', array("selector"=>"#users-form :input:not(:button)",
            "tooltip"=>array(
                "opacity"=>1,
                "position"=>"top center",
                "cancelDefault"=>TRUE,
        ), ));?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'users-form',
	'enableAjaxValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,)
)); ?>
	<fieldset class="form" style="width: auto;padding: 20px 0 20px 0">
		<legend>Complete los datos del usuario</legend>
		<?php echo $form->error($model,'userName'); ?>
		<?php echo $form->error($model,'userPass'); ?>
	<?php //echo $form->errorSummary($model); ?>

	<p>
		<?php echo $form->labelEx($model,'userName'); ?>
		<?php echo $form->textField($model,'userName',array('size'=>60,'maxlength'=>255, 'title'=>'Nombre del usuario a crear/editar', 'readonly'=>$model->isNewRecord ? false : true)); ?>
		
	</p>
	<?php
		if(!$model->isNewRecord)
		{	?>
	<div id="password" style="display:none">
	<?php }else{?>
	<div id="password">
	<?php }?>
	<p>		
		<?php echo $form->labelEx($model,'userPass'); ?>
		<?php echo $form->passwordField($model,'userPass',array('size'=>60,'maxlength'=>255, 'title'=>'defina una contraseña para el usuario'));?>
	</p>
	</div>
		<?php
		if(!$model->isNewRecord)
		{	
			echo '<p>';
			echo CHtml::label('Cambiar Contraseña:', 'changePassword');
			echo CHtml::checkBox('changePassword',false,array('onClick'=>'togglePassword();'));
			echo '</p>';
		}
		?>
	

	<p>
		<?php echo $form->labelEx($model,'is_admin'); ?>
		<?php echo $form->checkBox($model,'is_admin',array('title'=>'al ser administrador tendra acceso al menu Administracion')); ?>
		<?php echo $form->error($model,'is_admin'); ?>
	</p>

	<p style="margin-top: 50px;"><b>PERMISOS A GRUPOS</b></p><hr>
	<p>
		<?php
			echo CHtml::activeCheckBoxList($model,
					'GroupsIds',
					Chtml::listData(Groups::model()->findAll(),'group_id','group_name'),
                                        array('title'=>'active para que el usuario pertenezca a este grupo')
			);
		?>
	</p>

	<p>
		<button type="submit" name="Submit">
		<?php echo CHtml::image('../images/user_add.png',$model->isNewRecord ? 'Crear' : 'Actualizar');?>
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
	$("#newUser").hide();
	$("#buttons").show();
	$("#users-grid").show();
	
}
function togglePassword()
{
	$("#password").toggle();
}
</script>