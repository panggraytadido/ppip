<?php
$this->pageTitle = "Profile";

$this->breadcrumbs=array(
    'Profile',
);
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">

                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Profile</b>
                    </div>
                    <div class="pull-right">
                        <?php echo CHtml::link('<li class="fa fa-pencil"></li> Ubah Password',array('changepassword'), array('class'=>'btn btn-warning btn-xs')); ?>
                    </div>
                </div>
                
                <div class="ibox-content">
                    
                <?php if(Yii::app()->user->hasFlash('profileMessage')): ?>
                    <div class="alert alert-success fade in">
                        <button type="button" class="close close-sm" data-dismiss="alert">
                            <i class="fa fa-times"></i>
                        </button>
                    <?php echo Yii::app()->user->getFlash('profileMessage'); ?>
                    </div>
                <?php endif; ?>
                    
        <?php 
            $attributes = array(
                    array(
                        'name' => 'first_name',
                        'header' => 'Nama Awal',
                        'value' => $profile->first_name,
                    ),
                    array(
                        'name' => 'last_name',
                        'header' => 'Nama Akahir',
                        'value' => $profile->last_name,
                    ),
                    array(
                            'name' => 'username',
                            'value' => $model->username,
                    ),
                    array(
                            'name' => 'email',
                            'value' => $model->email,
                    ),
                    array(
                            'name' => 'lastvisit_at',
                            'value' => date("d-m-Y H:i:s", strtotime($model->lastvisit_at)),
                    ),
            );

            $this->widget('booster.widgets.TbDetailView', array(
                'data'=>$model,
                'attributes'=>$attributes,
            ));
        ?>
                    
                </div>
                
            </div>
        </div>
    </div>
</div>
