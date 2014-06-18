<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'rotulo_id'); ?>
		<?php echo $form->textField($model,'rotulo_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'rotulo_desc'); ?>
		<?php echo $form->textField($model,'rotulo_desc',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->