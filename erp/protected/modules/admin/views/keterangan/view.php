<?php
/* @var $this KeteranganController */
/* @var $model Keterangan */

$this->breadcrumbs=array(
	'Keterangans'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Keterangan', 'url'=>array('index')),
	array('label'=>'Create Keterangan', 'url'=>array('create')),
	array('label'=>'Update Keterangan', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Keterangan', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Keterangan', 'url'=>array('admin')),
);
?>

<h1>View Keterangan #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'kode',
		'Nama',
	),
)); ?>
