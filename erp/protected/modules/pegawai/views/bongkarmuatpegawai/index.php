<?php
$this->pageTitle = "Pegawai - Bongkar Muat";

$this->breadcrumbs=array(
	'Pegawai',
        'Bongkar Muat',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Data Bongkar Muat : Bulan <?php echo date("M Y"); ?></b>
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>
                    </div>
                </div>
                
                <div class="ibox-content">
                    
<?php $this->widget('booster.widgets.TbGridView', array(
	'id'=>'bongkarmuatpegawai-grid',
        'type' => 'striped bordered hover',
	'dataProvider'=>$model->searchBongkarmuatpegawai(),
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
                                'name' => 'Bongkarmuatpegawai[tanggal]',
                                'attribute' => 'tanggal',
                                'htmlOptions' => array(
                                                    'id' => 'Bongkarmuatpegawai_tanggal_index',
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
		array('name'=>'penerimaanbarangid','value'=>'($data->penerimaanbarangid!=null && $data->penerimaanbarangid!=0) ? "Ya" : "-"','header'=>'Penerimaan Barang'),
                array('name'=>'penjualanbarangid','value'=>'($data->penjualanbarangid!=null && $data->penjualanbarangid!=0) ? "Ya" : "-"','header'=>'Penjualan Barang'),
                array('name'=>'upah','value'=>'number_format($data->upah, 2, \',\', \'.\')','header'=>'Upah','type'=>'raw'),
	),
)); ?>

                    <div class="pull-right"><h3><b>Total Upah : <?php echo number_format($totalUpah, 2, ',', '.'); ?></b></h3></div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<?php
Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    $('#Bongkarmuatpegawai_tanggal_index').datepicker();
}
");
?>