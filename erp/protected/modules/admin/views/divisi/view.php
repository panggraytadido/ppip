<?php
/* @var $this DivisiController */
/* @var $model Divisi */

$this->breadcrumbs=array(
	'Divisis'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Divisi', 'url'=>array('index')),
	array('label'=>'Create Divisi', 'url'=>array('create')),
	array('label'=>'Update Divisi', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Divisi', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Divisi', 'url'=>array('admin')),
);
?>

<h1>View Divisi #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'kode',
		'nama',
	),
)); ?>
