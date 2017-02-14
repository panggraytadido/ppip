<?php
$this->pageTitle = "Ubah User";

$this->breadcrumbs=array(
	'User' => array('/user/admin'),
	$model->username=>array('view','id'=>$model->id),
	'Ubah',
);
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <h4>Ubah User "<?php echo $model->username; ?>"</h4>
                    </div>
                    <div class="pull-right">
                        <?php echo CHtml::link('<li class="fa fa-mail-reply"></li> Kembali',array('admin'), array('class'=>'btn btn-default btn-xs')); ?>
                        <?php echo CHtml::link('<li class="fa fa-search-plus"></li> Detail',array('view','id'=>$model->id), array('class'=>'btn btn-success btn-xs')); ?>
                        <?php echo CHtml::link('<li class="fa fa-plus"></li> Tambah',array('create'), array('class'=>'btn btn-primary btn-xs')); ?>
                    </div>
                </div>
                
                <div class="ibox-content">


<?php
	echo $this->renderPartial('_form', array('model'=>$model, 'profile'=>$profile, 'userskaryawan'=>$userskaryawan));
?>

                </div>
            </div>
        </div>
    </div>
</div>