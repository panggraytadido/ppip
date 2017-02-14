<?php
/* @var $this KeteranganController */
/* @var $model Keterangan */

$this->breadcrumbs=array(
	'Keterangans'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Keterangan', 'url'=>array('index')),
	array('label'=>'Manage Keterangan', 'url'=>array('admin')),
);
?>

<h1>Create Keterangan</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>