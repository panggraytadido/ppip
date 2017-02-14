<?php
/* @var $this PelangganController */
/* @var $model Pelanggan */

$this->breadcrumbs=array(
	'Pelanggans'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Pelanggan', 'url'=>array('index')),
	array('label'=>'Create Pelanggan', 'url'=>array('create')),
	array('label'=>'View Pelanggan', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Pelanggan', 'url'=>array('admin')),
);
?>

<h1>Update Pelanggan <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>