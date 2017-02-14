<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form CActiveForm */

$this->pageTitle=Yii::app()->name . ' - Contact Us';
$this->breadcrumbs=array(
	'Contact',
);
?>

<h1>Contact Us</h1>

<?php if(Yii::app()->user->hasFlash('contact')): ?>

<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('contact'); ?>
</div>

<?php else: ?>


<div class="form">

<?php 
$form=$this->beginWidget('booster.widgets.TbActiveForm',array(
	'id'=>'tlu-kategorialatuji-form',
	'enableAjaxValidation'=>false,
        'type'=>'horizontal'
)); 
?>


	

	<?php echo $form->errorSummary($model); ?>

        
	<?php echo $form->textFieldGroup($model, 'name',array('wrapperHtmlOptions'=>array('class' => 'col-sm-2',))); ?>

	

	<div class="form-actions">    
              
            <?php echo CHtml::link('Back',array('index'), array('class'=>'btn btn-primary')); ?>
              
            <?php $this->widget('booster.widgets.TbButton', array(
                                'buttonType'=>'submit',
                                'context'=>'primary',
                                'label'=>'ad',
                                'htmlOptions'=>array(
                                   'confirm'=>'Are You Sure Confirm all record',                                   
                                )
                        )); ?>            
        </div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php endif; ?>