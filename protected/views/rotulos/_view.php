<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('rotulo_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->rotulo_id), array('view', 'id'=>$data->rotulo_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rotulo_desc')); ?>:</b>
	<?php echo CHtml::encode($data->rotulo_desc); ?>
	<br />


</div>