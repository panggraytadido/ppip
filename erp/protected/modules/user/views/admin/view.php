<?php
$this->pageTitle = "Detail User";

$this->breadcrumbs=array(
	'User' => array('/user/admin'),
	$model->username,
);
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>User : <?php echo $model->username; ?></b>
                    </div>
                    <div class="pull-right">
                        <?php echo CHtml::link('<li class="fa fa-mail-reply"></li> Kembali',array('admin'), array('class'=>'btn btn-default btn-xs')); ?>
                        <?php echo CHtml::link('<li class="fa fa-pencil"></li> Ubah',array('update','id'=>$model->id), array('class'=>'btn btn-warning btn-xs')); ?>
                        <?php echo CHtml::link('<li class="fa fa-plus"></li> Tambah',array('create'), array('class'=>'btn btn-primary btn-xs')); ?>
                    </div>
                </div>
            
                <div class="ibox-content">
                    
                <?php if(Yii::app()->user->hasFlash('success')):?>
                    <div class="alert alert-success fade in">
                        <button type="button" class="close close-sm" data-dismiss="alert">
                            <i class="fa fa-times"></i>
                        </button>
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                    </div>
                <?php endif; ?>
        
        <?php
 
	$attributes = array(
		//'id',
		'username',
	);
	
	$profileFields=ProfileField::model()->forOwner()->sort()->findAll();
	if ($profileFields) {
		foreach($profileFields as $field) {
			array_push($attributes,array(
					'label' => UserModule::t($field->title),
					'name' => $field->varname,
					'type'=>'raw',
					'value' => (($field->widgetView($model->profile))?$field->widgetView($model->profile):(($field->range)?Profile::range($field->range,$model->profile->getAttribute($field->varname)):$model->profile->getAttribute($field->varname))),
				));
		}
	}

    	array_push($attributes,
		//'password',
		'email',
		'activkey',
		array(
			'name' => 'create_at',
			'value' => date("d-m-Y H:i:s", strtotime($model->create_at)),
		),
		'lastvisit_at',
		array(
			'name' => 'superuser',
			'value' => User::itemAlias("AdminStatus",$model->superuser),
		),
		array(
			'name' => 'status',
			'value' => User::itemAlias("UserStatus",$model->status),
		)
	);
        
        $userskaryawan = Userskaryawan::model()->findByAttributes(array('usersid'=>$model->id));
        array_push($attributes,
                array(
                        'name' => 'karyawan',
                        'value' => ($userskaryawan!=null) ? $userskaryawan->karyawan->nama : '',
                ),
                array(
                        'name' => 'Divisi',
                        'value' => ($userskaryawan!=null) ? $userskaryawan->divisi->nama : '',
                ),
                array(
                        'name' => 'Lokasi',
                        'value' => ($userskaryawan!=null) ? $userskaryawan->lokasipenyimpananbarang->nama : '',
                )
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