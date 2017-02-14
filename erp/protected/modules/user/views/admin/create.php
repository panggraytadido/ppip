<?php
$this->pageTitle = "Tambah User";

$this->breadcrumbs=array(
    'User' => array('/user/admin'),
    'Tambah User Baru'
);
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        Tambah User Baru
                    </div>
                    <div class="pull-right">
                        <?php echo CHtml::link('<li class="fa fa-mail-reply"></li> Kembali',array('admin'), array('class'=>'btn btn-default btn-xs')); ?>
                    </div>
                </div>
                
                <div class="ibox-content">

<?php
echo $this->renderPartial('_form', array('model'=>$model,'profile'=>$profile, 'userskaryawan'=>$userskaryawan));
?>

                </div>
            </div>
        </div>
    </div>
</div>