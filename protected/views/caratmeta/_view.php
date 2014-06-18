<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('carat_meta_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->carat_meta_id), array('view', 'id'=>$data->carat_meta_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('carat_meta_desc')); ?>:</b>
	<?php echo CHtml::encode($data->carat_meta_desc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('carat_meta_label')); ?>:</b>
	<?php echo CHtml::encode($data->carat_meta_label); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('doc_type_id')); ?>:</b>
	<?php echo CHtml::encode($data->doc_type_id); ?>
	<br />


</div>