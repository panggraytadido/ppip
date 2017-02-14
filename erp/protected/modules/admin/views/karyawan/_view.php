<?php
/* @var $this KaryawanController */
/* @var $data Karyawan */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nik')); ?>:</b>
	<?php echo CHtml::encode($data->nik); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nama')); ?>:</b>
	<?php echo CHtml::encode($data->nama); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('jeniskelamin')); ?>:</b>
	<?php echo CHtml::encode($data->jeniskelamin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('alamat')); ?>:</b>
	<?php echo CHtml::encode($data->alamat); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tanggallahir')); ?>:</b>
	<?php echo CHtml::encode($data->tanggallahir); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('jeniskaryawaniid')); ?>:</b>
	<?php echo CHtml::encode($data->jeniskaryawaniid); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('tempatlahir')); ?>:</b>
	<?php echo CHtml::encode($data->tempatlahir); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hp')); ?>:</b>
	<?php echo CHtml::encode($data->hp); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('jabatanid')); ?>:</b>
	<?php echo CHtml::encode($data->jabatanid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('photo')); ?>:</b>
	<?php echo CHtml::encode($data->photo); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('createddate')); ?>:</b>
	<?php echo CHtml::encode($data->createddate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updatedate')); ?>:</b>
	<?php echo CHtml::encode($data->updatedate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('userid')); ?>:</b>
	<?php echo CHtml::encode($data->userid); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tmtmasuk')); ?>:</b>
	<?php echo CHtml::encode($data->tmtmasuk); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tmtresign')); ?>:</b>
	<?php echo CHtml::encode($data->tmtresign); ?>
	<br />

	*/ ?>

</div>