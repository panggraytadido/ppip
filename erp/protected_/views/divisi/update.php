<?php
/* @var $this DivisiController */
/* @var $model Divisi */

$this->breadcrumbs=array(
	'Divisis'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Divisi', 'url'=>array('index')),
	array('label'=>'Create Divisi', 'url'=>array('create')),
	array('label'=>'View Divisi', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Divisi', 'url'=>array('admin')),
);
?>

<h1>Update Divisi <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>