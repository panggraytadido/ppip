<?php
/* @var $this TransferkasirController */
/* @var $model Transferkasir */

$this->breadcrumbs=array(
	'Transferkasirs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Transferkasir', 'url'=>array('index')),
	array('label'=>'Manage Transferkasir', 'url'=>array('admin')),
);
?>

<h1>Create Transferkasir</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>