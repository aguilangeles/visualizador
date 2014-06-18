<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'doc_type_id'); ?>
		<?php echo $form->textField($model,'doc_type_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'doc_type_desc'); ?>
		<?php echo $form->textField($model,'doc_type_desc',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'doc_type_label'); ?>
		<?php echo $form->textField($model,'doc_type_label',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'doc_type_level'); ?>
		<?php echo $form->textField($model,'doc_type_level'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'enabled'); ?>
		<?php echo $form->textField($model,'enabled'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->