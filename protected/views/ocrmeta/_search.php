<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'ocr_meta_id'); ?>
		<?php echo $form->textField($model,'ocr_meta_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ocr_meta_desc'); ?>
		<?php echo $form->textField($model,'ocr_meta_desc',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ocr_meta_label'); ?>
		<?php echo $form->textField($model,'ocr_meta_label',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'doc_type_id'); ?>
		<?php echo $form->textField($model,'doc_type_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->