<?php
/* @var $this BarangController */
/* @var $data Barang */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('divisiid')); ?>:</b>
	<?php echo CHtml::encode($data->divisiid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('supplierid')); ?>:</b>
	<?php echo CHtml::encode($data->supplierid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('kode')); ?>:</b>
	<?php echo CHtml::encode($data->kode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nama')); ?>:</b>
	<?php echo CHtml::encode($data->nama); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hargamodal')); ?>:</b>
	<?php echo CHtml::encode($data->hargamodal); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hargaeceran')); ?>:</b>
	<?php echo CHtml::encode($data->hargaeceran); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('hargagrosir')); ?>:</b>
	<?php echo CHtml::encode($data->hargagrosir); ?>
	<br />

	*/ ?>

</div>