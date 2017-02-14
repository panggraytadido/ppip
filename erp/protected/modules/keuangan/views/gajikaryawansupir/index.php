<?php
$this->pageTitle = "Penggajian - Karyawan Supir";

$this->breadcrumbs=array(
	'Penggajian',
        'Karyawan Supir',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Penggajian - Karyawan Supir</b>
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
	'dataProvider'=>$model->searchKaryawansupir(),
	'filter'=>$model,
        'afterAjaxUpdate' => 'reinstallDatePicker', 
	'columns'=>array(
		array(
                    'header'=>'No',
                    'class'=>'IndexColumn',
                ),		
		array('name'=>'tanggal','value'=>'date("d-m-Y", strtotime($data->tanggal))','header'=>'Tanggal',
                        'filter'=>  $this->widget(
                            'booster.widgets.TbDatePicker',
                            array(
                                'name' => 'Karyawansupir[tanggal]',
                                'attribute' => 'tanggal',
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
		array('name'=>'nik','value'=>'$data->nik','header'=>'NIK'),
                array('name'=>'namakaryawan','value'=>'$data->namakaryawan','header'=>'Karyawan'),
                array('name'=>'tujuan','value'=>'$data->tujuan','header'=>'Tujuan'),
                //array('name'=>'kendaraan','value'=>'$data->kendaraan','header'=>'Kendaraan'),
                //array('name'=>'biaya','value'=>'$data->biaya','header'=>'Biaya'),
                //array('name'=>'bbm','value'=>'$data->bbm','header'=>'BBM'),
                array('name'=>'upah','value'=>'number_format($data->upah)','header'=>'Upah'),
	),
)); ?>

                </div>
            </div>	              
        </div>
    </div>
</div>

<?php
Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    $('#Karyawansupir_tanggal').datepicker();
}
");
?>