<?php
$this->breadcrumbs=array(
	'Ocr Metas'=>array('index'),
	$model->ocr_meta_id,
);

$this->menu=array(
	array('label'=>'List OcrMeta', 'url'=>array('index')),
	array('label'=>'Create OcrMeta', 'url'=>array('create')),
	array('label'=>'Update OcrMeta', 'url'=>array('update', 'id'=>$model->ocr_meta_id)),
	array('label'=>'Delete OcrMeta', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->ocr_meta_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage OcrMeta', 'url'=>array('admin')),
);
?>

<h1>View OcrMeta #<?php echo $model->ocr_meta_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ocr_meta_id',
		'ocr_meta_desc',
		'ocr_meta_label',
		'doc_type_id',
	),
)); ?>
