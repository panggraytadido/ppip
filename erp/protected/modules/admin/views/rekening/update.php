<?php
/* @var $this RekeningController */
/* @var $model Rekening */

$this->breadcrumbs=array(
	'Rekenings'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Rekening', 'url'=>array('index')),
	array('label'=>'Create Rekening', 'url'=>array('create')),
	array('label'=>'View Rekening', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Rekening', 'url'=>array('admin')),
);
?>

<h1>Update Rekening <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>