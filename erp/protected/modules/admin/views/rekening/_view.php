<?php
/* @var $this RekeningController */
/* @var $data Rekening */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('norekening')); ?>:</b>
	<?php echo CHtml::encode($data->norekening); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('namabank')); ?>:</b>
	<?php echo CHtml::encode($data->namabank); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('namapemilik')); ?>:</b>
	<?php echo CHtml::encode($data->namapemilik); ?>
	<br />


</div>