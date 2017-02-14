<?php
/* @var $this KaryawanController */
/* @var $model Karyawan */

$this->breadcrumbs=array(
	'Karyawans'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Karyawan', 'url'=>array('index')),
	array('label'=>'Create Karyawan', 'url'=>array('create')),
	array('label'=>'Update Karyawan', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Karyawan', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Karyawan', 'url'=>array('admin')),
);
?>

<h1>View Karyawan #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'nik',
		'nama',
		'jeniskelamin',
		'alamat',
		'tanggallahir',
		'jeniskaryawaniid',
		'tempatlahir',
		'hp',
		'jabatanid',
		'photo',
		'createddate',
		'updatedate',
		'userid',
		'tmtmasuk',
		'tmtresign',
	),
)); ?>
