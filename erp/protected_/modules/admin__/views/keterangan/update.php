<?php
/* @var $this KeteranganController */
/* @var $model Keterangan */

$this->breadcrumbs=array(
	'Keterangans'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Keterangan', 'url'=>array('index')),
	array('label'=>'Create Keterangan', 'url'=>array('create')),
	array('label'=>'View Keterangan', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Keterangan', 'url'=>array('admin')),
);
?>

<h1>Update Keterangan <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>