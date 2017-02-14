<?php 
$this->pageTitle = "Ubah Password";

$this->breadcrumbs=array(
    'Profile' => array('/user/profile'),
    "Ubah Password",
);
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">

                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Ubah Password<?php // echo $model->username; ?></b>
                    </div>
                    <div class="pull-right">
                        <?php echo CHtml::link('<li class="fa fa-user"></li> Profile',array('/user/profile'), array('class'=>'btn btn-info btn-xs')); ?>
                    </div>
                </div>
                
                <div class="ibox-content">
                    
        <?php $form=$this->beginWidget('booster.widgets.TbActiveForm', array(
            'id'=>'changepassword-form',
            'enableAjaxValidation'=>true,
            'type' => 'horizontal',
            'clientOptions'=>array(
                'validateOnSubmit'=>true,
            ),
        )); ?>

        <?php // echo $form->errorSummary($model); ?>
        <?php echo $form->passwordFieldGroup($model, 'oldPassword',array('wrapperHtmlOptions' => array('class' => 'col-sm-4'),'widgetOptions'=>array('htmlOptions'=>array('class'=>'col-sm-2'))));?>
        <?php echo $form->passwordFieldGroup($model, 'password',array('wrapperHtmlOptions' => array('class' => 'col-sm-4'),'widgetOptions'=>array('htmlOptions'=>array('class'=>'col-sm-2'))));?>
        <?php echo $form->passwordFieldGroup($model, 'verifyPassword',array('wrapperHtmlOptions' => array('class' => 'col-sm-4'),'widgetOptions'=>array('htmlOptions'=>array('class'=>'col-sm-2'))));?>

        <div class="pull-right">
            <button type="submit" name="btnSimpan" class="btn btn-primary"><li class="fa fa-check"></li> Simpan</button>
        </div>
                    
        <?php $this->endWidget(); ?>
                    
        <div class="clearfix"></div>
    
                </div>
            </div>
        </div>
    </div>
</div>