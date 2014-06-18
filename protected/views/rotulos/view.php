<?php
$this->breadcrumbs=array(
	'Rotuloses'=>array('index'),
	$model->rotulo_id,
);

$this->menu=array(
	array('label'=>'List Rotulos', 'url'=>array('index')),
	array('label'=>'Create Rotulos', 'url'=>array('create')),
	array('label'=>'Update Rotulos', 'url'=>array('update', 'id'=>$model->rotulo_id)),
	array('label'=>'Delete Rotulos', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->rotulo_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Rotulos', 'url'=>array('admin')),
);
?>

<h1>View Rotulos #<?php echo $model->rotulo_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'rotulo_id',
		'rotulo_desc',
	),
)); ?>
