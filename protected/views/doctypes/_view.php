<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('doc_type_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->doc_type_id), array('view', 'id'=>$data->doc_type_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('doc_type_desc')); ?>:</b>
	<?php echo CHtml::encode($data->doc_type_desc); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('doc_type_label')); ?>:</b>
	<?php echo CHtml::encode($data->doc_type_label); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('doc_type_level')); ?>:</b>
	<?php echo CHtml::encode($data->doc_type_level); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('enabled')); ?>:</b>
	<?php echo CHtml::encode($data->enabled); ?>
	<br />


</div>