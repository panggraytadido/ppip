<?php
/* @var $this DivisiController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Divisis',
);

$this->menu=array(
	array('label'=>'Create Divisi', 'url'=>array('create')),
	array('label'=>'Manage Divisi', 'url'=>array('admin')),
);
?>

<h1>Divisis</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
