<?php
$this->pageTitle = "Penggajian - Karyawan Gudang";

$this->breadcrumbs=array(
	'Penggajian',
        'Karyawan Gudang',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Penggajian - Karyawan Gudang</b>
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>
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
	'id'=>'karyawangudang-grid',
        'type' => 'striped bordered hover',
        'responsiveTable'=>true,
	'dataProvider'=>$model->searchKaryawangudang(),
	'filter'=>$model,
        'afterAjaxUpdate' => 'reinstallDatePicker', 
	'columns'=>array(
		array(
                    'header'=>'No',
                    'class'=>'IndexColumn',
                ),		
		array('name'=>'tanggalbm','value'=>'$data->tanggalbm','header'=>'Tanggal',
                        'filter'=>  $this->widget(
                            'booster.widgets.TbDatePicker',
                            array(
                                'name' => 'Karyawangudang[tanggalbm]',
                                'attribute' => 'tanggalbm',
                                'htmlOptions' => array(
                                                    'class'=>'form-control ct-form-control',
                                                ),
                                'options' => array(
                                    'format' => 'yyyy-mm-dd',
                                    'language' => 'en',
                                    'class'=>'form-controls form-control-inline input-medium default-date-picker',
                                    'size'=>"16"
                                )
                            ),true
                        )
                ),
		array('name'=>'namakaryawan','value'=>'$data->namakaryawan','header'=>'Karyawan'),
                array('name'=>'namabarang','value'=>'$data->namabarang','header'=>'Barang Netto'),
                array('name'=>'upah','value'=>'$data->upah','header'=>'Upah'),
                array('name'=>'keterangan','value'=>'$data->keterangan','header'=>'Keterangan','filter'=>''),
	),
)); ?>

                </div>
            </div>	              
        </div>
    </div>
</div>