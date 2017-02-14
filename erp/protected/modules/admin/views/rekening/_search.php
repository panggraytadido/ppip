<?php
/* @var $this RekeningController */
/* @var $model Rekening */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'norekening'); ?>
		<?php echo $form->textField($model,'norekening'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'namabank'); ?>
		<?php echo $form->textField($model,'namabank',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'namapemilik'); ?>
		<?php echo $form->textField($model,'namapemilik',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->