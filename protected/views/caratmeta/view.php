<?php
$this->breadcrumbs=array(
	'Carat Metas'=>array('index'),
	$model->carat_meta_id,
);

$this->menu=array(
	array('label'=>'List CaratMeta', 'url'=>array('index')),
	array('label'=>'Create CaratMeta', 'url'=>array('create')),
	array('label'=>'Update CaratMeta', 'url'=>array('update', 'id'=>$model->carat_meta_id)),
	array('label'=>'Delete CaratMeta', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->carat_meta_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CaratMeta', 'url'=>array('admin')),
);
?>

<h1>View CaratMeta #<?php echo $model->carat_meta_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'carat_meta_id',
		'carat_meta_desc',
		'carat_meta_label',
		'doc_type_id',
	),
)); ?>
