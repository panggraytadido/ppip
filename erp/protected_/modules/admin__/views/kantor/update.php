<?php
/* @var $this BagianController */
/* @var $model Bagian */

$this->breadcrumbs=array(
	'Bagians'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Bagian', 'url'=>array('index')),
	array('label'=>'Create Bagian', 'url'=>array('create')),
	array('label'=>'View Bagian', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Bagian', 'url'=>array('admin')),
);
?>

<h1>Update Bagian <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>