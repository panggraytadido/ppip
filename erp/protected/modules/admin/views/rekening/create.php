<?php
/* @var $this RekeningController */
/* @var $model Rekening */

$this->breadcrumbs=array(
	'Rekenings'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Rekening', 'url'=>array('index')),
	array('label'=>'Manage Rekening', 'url'=>array('admin')),
);
?>

<h1>Create Rekening</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>