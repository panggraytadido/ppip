<?php
$this->pageTitle = "User";

$this->breadcrumbs=array(
	'User',
);
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">

                <div class="ibox-title">
                    <div class="pull-left">
                        <b>User</b>
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>
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

            <?php $this->widget('booster.widgets.TbGridView', array(
                'id'=>'user-grid',
                'type' => 'striped bordered hover',
                'dataProvider'=>$model->search(),
                'filter'=>$model,
                'columns'=>array(
                    array(
                        'name' => 'username',
                        'type'=>'raw',
                        'value' => 'CHtml::link(UHtml::markSearch($data,"username")." (".$data->profile->first_name." ".$data->profile->last_name.") ",array("admin/view","id"=>$data->id))',
                    ),
                    array(
                        'name' => 'divisi',
                        'type'=>'raw',
                        'value' => '$data->userskaryawan->divisi->nama',
                        'filter'=>  CHtml::dropDownList('User[divisi]','nama',
                                    CHtml::listData(Divisi::model()->findAll(),'nama','nama'),
                                    array(
                                        'id'=>'User_divisi',
                                        'class'=>"form-control",
                                        'prompt'=>'Pilih Divisi',
                                    )),
                    ),
                    array(
                        'name'=>'lastvisit_at',
                        'value'=>'date("d-m-Y H:i:s", strtotime($data->lastvisit_at))',
                        'filter'=>'',
                    ),
                    array(
                        'name'=>'superuser',
                        'value'=>'User::itemAlias("AdminStatus",$data->superuser)',
                        'filter'=>User::itemAlias("AdminStatus"),
                    ),
                    array(
                        'name'=>'status',
                        'value'=>'User::itemAlias("UserStatus",$data->status)',
                        'filter' => User::itemAlias("UserStatus"),
                    ),
                    array(
                        'class'=>'booster.widgets.TbButtonColumn',
                        'template'=>'{view} {update} {delete}',
                        'buttons'=>array(
                             'view' => array(
                                 'label'=>'Lihat',
                                 'icon'=>'fa fa-search-plus',
                                 'options'=>array(
                                     'class'=>'btn btn-success btn-xs',
                                 ),
                              ),
                            'update' => array
                            (
                                'label'=>'Ubah',
                                'icon'=>'fa fa-pencil',
                                'options'=>array(
                                    'class'=>'btn btn-primary btn-xs',
                                ),
                            ),
                            'delete' => array
                            (
                                'label'=>'Hapus',
                                'icon'=>'fa fa-trash-o',
                                'options'=>array(
                                    'class'=>'btn btn-danger btn-xs',
                                ),
                            ),
                        ),
                    ),
                ),
                'afterAjaxUpdate'=>'function(){
                      jQuery("#'.CHtml::activeId($model, 'create_at').'").datepicker({"format":"yyyy-mm-dd","language":"en"});

                }',

            )); ?>

                </div>
            </div>
        </div>
    </div>
</div>