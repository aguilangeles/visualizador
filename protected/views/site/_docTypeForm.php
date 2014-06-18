<div>
	<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'search-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>false,
		),
	)); ?>
	<div>
		<?php CHtml::label('Nivel 1', 'nivel1');?>
		<?php //$form->dropDownList($model,);?>
	</div>
	<?php $this->endWidget(); ?>
</div>