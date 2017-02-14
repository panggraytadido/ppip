<?php
/* @var $this TransferkasirController */
/* @var $model Transferkasir */

$this->breadcrumbs=array(
	'Transferkasirs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Transferkasir', 'url'=>array('index')),
	array('label'=>'Create Transferkasir', 'url'=>array('create')),
	array('label'=>'View Transferkasir', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Transferkasir', 'url'=>array('admin')),
);
?>

<h1>Update Transferkasir <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>