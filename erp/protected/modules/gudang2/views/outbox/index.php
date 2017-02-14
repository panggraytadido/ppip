<?php
$this->pageTitle = "Outbox - Gudang";

$this->breadcrumbs=array(
    'Gudang',
    'Outbox',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Gudang - Outbox</b>
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>                       
                    </div>
                </div>
                
                <div class="ibox-content">
                        
<?php $this->widget('booster.widgets.TbGridView', array(
	'id'=>'gudangpenjualanbarang-grid',
        'type' => 'striped bordered hover',
        'responsiveTable'=>true,
	'dataProvider'=>$model->search(),
	'filter'=>$model,
        'afterAjaxUpdate' => 'reinstallDatePicker', 
	'columns'=>array(
		array(
                    'header'=>'No',
                    'class'=>'IndexColumn',
                ),		
		array('name'=>'kode','value'=>'$data->kode','header'=>'Kode Barang'),
		array('name'=>'nama','value'=>'$data->nama','header'=>'Nama Barang'),
                array('name'=>'namapelanggan','value'=>'$data->namapelanggan','header'=>'Pelanggan'),
                array('name'=>'tanggal','value'=>'date("d-m-Y", strtotime($data->tanggal))','header'=>'Tanggal',
                        'filter'=>  $this->widget(
                            'booster.widgets.TbDatePicker',
                            array(
                                'name' => 'Outbox[tanggal]',
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
                array('name'=>'jumlah','value'=>'$data->jumlah','header'=>'Jumlah',
                        'filter'=>CHtml::textField('Outbox[jumlah]','',array(
                                'id' => 'Gudangpenjualanbarang_jumlah_index',
                                'class' => 'form-control',
                            )),'filter'=>false
                ),
                array('name'=>'box','value'=>'$data->box','header'=>'Box','filter'=>false),
                array('name'=>'hargasatuan','value'=>'number_format($data->hargasatuan)','header'=>'Harga','filter'=>false),
                array('name'=>'hargatotal','value'=>'number_format($data->hargatotal)','header'=>'Total Harga','filter'=>false),
		array(
                    'header'=>'Action',
                    'class'=>'booster.widgets.TbButtonColumn',
                    'template'=>'{view} {update}',
                                    'buttons' => array(
                                        'view' =>   array(
                                                       'label'=>'detail',
                                                       'icon'=>'fa fa-search-plus',
                                                       'url'=>'Yii::app()->createUrl("gudang/penjualanbarang/view", array("id"=>$data->id))',
                                                        'options'=>array(
                                                            'class'=>'btn btn-success btn-xs',
                                                        ),
                                                    ),
                                        'update' =>   array(
                                                       'label'=>'edit',
                                                       'icon'=>'fa fa-edit',
                                                       'url'=>'Yii::app()->createUrl("gudang/penjualanbarang/update", array("id"=>$data->id))',
                                                        'options'=>array(
                                                            'class'=>'btn btn-success btn-xs',
                                                            'ajax'=>array(                                                                                    
                                                                'type'=>'POST',
                                                                'url'=>"js:$(this).attr('href')",
                                                                'success'=>'function(data) {
                                                                        $("#modal-update #body-update").html(data); 
                                                                        $("#modal-update").modal({backdrop: "static", keyboard: false});
                                                                }'
                                                            ),
                                                        ),
                                                    ),
                                        ),
                        ),
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
    $('#Gudangpenjualanbarang_tanggal').datepicker();
}
");
?>

<!-- modal create -->
<div class="modal fade bs-example-modal-sm" id="modal-create" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Tambah Data Penjualan Barang</h4>
        </div>
        <div class="modal-body" id="body-create">
             
        </div>
        <div class="modal-footer">
            &nbsp;
        </div>

      </div>
    </div>
  </div>
<!-- -->

<!-- modal update -->
<div class="modal fade bs-example-modal-sm" id="modal-update" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Update Data Penjualan Barang</h4>
        </div>
        <div class="modal-body" id="body-update">
            
        </div>
        <div class="modal-footer">
          &nbsp;
        </div>

      </div>
    </div>
</div>
