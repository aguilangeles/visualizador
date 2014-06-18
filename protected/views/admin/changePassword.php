<h1>Cambiar contraseña</h1>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ChangePassword-form',
	'enableClientValidation'=>true,
	//'enableAjaxValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">Los campos con <span class="required">*</span> son requeridos.</p>


	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'newPassword'); ?>
		<?php echo $form->passwordField($model,'newPassword'); ?>
		<?php echo $form->error($model,'newPassword'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'newPassword2'); ?>
		<?php echo $form->passwordField($model,'newPassword2'); ?>
		<?php echo $form->error($model,'newPassword2'); ?>
	</div>
	<div id="message">
		
	</div>
	<div class="row buttons">
		<?php //echo CHtml::submitButton('Cambiar'); ?>
		<?php echo CHtml::ajaxSubmitButton('Cambiar','ChangePassword2',array('update'=>'#message')) ?>
	</div>
</div>
<?php $this->endWidget(); ?>

