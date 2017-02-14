<?php
/* @var $this RekeningController */
/* @var $model Rekening */

$this->breadcrumbs=array(
	'Rekenings'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Rekening', 'url'=>array('index')),
	array('label'=>'Create Rekening', 'url'=>array('create')),
	array('label'=>'Update Rekening', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Rekening', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Rekening', 'url'=>array('admin')),
);
?>

<h1>View Rekening #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'norekening',
		'namabank',
		'namapemilik',
	),
)); ?>
