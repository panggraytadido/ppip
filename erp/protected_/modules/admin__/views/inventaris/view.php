<?php
/* @var $this BagianController */
/* @var $model Bagian */

$this->breadcrumbs=array(
	'Bagians'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Bagian', 'url'=>array('index')),
	array('label'=>'Create Bagian', 'url'=>array('create')),
	array('label'=>'Update Bagian', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Bagian', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Bagian', 'url'=>array('admin')),
);
?>

<h1>View Bagian #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'divisiid',
		'kode',
		'nama',
		'keterangan',
	),
)); ?>
