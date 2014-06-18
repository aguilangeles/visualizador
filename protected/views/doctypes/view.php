<?php
$this->breadcrumbs=array(
	'Doc Types'=>array('index'),
	$model->doc_type_id,
);

$this->menu=array(
	array('label'=>'List DocTypes', 'url'=>array('index')),
	array('label'=>'Create DocTypes', 'url'=>array('create')),
	array('label'=>'Update DocTypes', 'url'=>array('update', 'id'=>$model->doc_type_id)),
	array('label'=>'Delete DocTypes', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->doc_type_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage DocTypes', 'url'=>array('admin')),
);
?>

<h1>View DocTypes #<?php echo $model->doc_type_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'doc_type_id',
		'doc_type_desc',
		'doc_type_label',
		'doc_type_level',
		'enabled',
	),
)); ?>
