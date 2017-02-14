<?php
/* @var $this BagianController */
/* @var $model Bagian */

$this->breadcrumbs=array(
	'Bagians'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Bagian', 'url'=>array('index')),
	array('label'=>'Manage Bagian', 'url'=>array('admin')),
);
?>

<h1>Create Bagian</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>