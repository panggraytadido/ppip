<?php
$this->pageTitle = "Penjualan Barang - Hygienis";

$this->breadcrumbs=array(
    'Hygienis',
    'Penjualan Barang',
);

?>
<br>
<?php if(Yii::app()->user->hasFlash('success')):?>
                        <div class="alert alert-success fade in">
                            <button type="button" class="close close-sm" data-dismiss="alert">
                                <i class="fa fa-times"></i>
                            </button>
                            <?php echo Yii::app()->user->getFlash('success'); ?>
                        </div>    
<?php endif; ?>

<div class="col-lg-12">
    <div id="AjaxLoader" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/themes/inspinia/img/loader.gif"></img></div>    
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Hygienis - Penjualan Barang</b>
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>
                        <?php 
                            echo CHtml::ajaxButton('Tambah',CHtml::normalizeUrl(array('penjualanbarang/create')),
                                array(
                                    'type'=>'POST',                      
                                    'url'=>"$(this).attr('href')",
                                    'beforeSend'=>'js:function(){$("#AjaxLoader").show();}',
                                    'success'=>'js:function(data) 
                                     {    
                                        $("#AjaxLoader").hide();
                                        $("#modal-create #body-create").html(data); 
                                        $("#modal-create").modal({backdrop: "static", keyboard: false});
                                    }'               
                                ),array('id'=>'btnCreate','class'=>'btn btn-primary btn-xs'));
                        ?> 
                    </div>
                </div>
                
                <div class="ibox-content">

<?php $this->widget('booster.widgets.TbGridView', array(
	'id'=>'hygienispenjualanbarang-grid',
        'type' => 'striped bordered hover',
        'responsiveTable'=>true,
	'dataProvider'=>$model->searchPenjualanbarang(),
	'filter'=>$model,
        'afterAjaxUpdate' => 'reinstallDatePicker', 
	'columns'=>array(
		array(
                    'header'=>'No',
                    'class'=>'IndexColumn',
                ),
                array('name'=>'tanggal','value'=>'date("d-m-Y", strtotime($data->tanggal))','header'=>'Tanggal Penjualan',
                        'filter'=>  $this->widget(
                            'booster.widgets.TbDatePicker',
                            array(
                                'name' => 'Hygienispenjualanbarang[tanggal]',
                                'attribute' => 'tanggal',
                                'htmlOptions' => array(
                                                    'id' => 'Hygienispenjualanbarang_tanggal_index',
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
		array('name'=>'kode','value'=>'$data->kode','header'=>'Kode Barang'),
		array('name'=>'nama','value'=>'$data->nama','header'=>'Nama Barang'),
                array('name'=>'namapelanggan','value'=>'$data->namapelanggan','header'=>'Pelanggan'),
                array('name'=>'jumlah','value'=>'$data->jumlah','header'=>'Jumlah',
                        'filter' => CHtml::textField('Hygienispenjualanbarang[jumlah]','',array(
                            'id' => 'Hygienispenjualanbarang_jumlah_index',
                            'class' => 'form-control',
                        )),

                ),
                array('name'=>'hargasatuan','value'=>'number_format($data->hargasatuan)','header'=>'Harga'),
                array('name'=>'hargatotal','value'=>'number_format($data->hargatotal)','header'=>'Total Harga'),
		array(
                    'header'=>'Action',
                    'class'=>'booster.widgets.TbButtonColumn',
                    'template'=>'{view} {update} {delete}',
                                    'buttons' => array(
                                        'view' =>   array(
                                                       'label'=>'detail',
                                                       'icon'=>'fa fa-search-plus',
                                                       'url'=>'Yii::app()->createUrl("hygienis/penjualanbarang/view", array("id"=>$data->id))',
                                                        'options'=>array(
                                                            'class'=>'btn btn-success btn-xs',
                                                        ),
                                                    ),
                                        'update' =>   array(
                                                       'label'=>'edit',
                                                       'icon'=>'fa fa-edit',
                                                       'url'=>'Yii::app()->createUrl("hygienis/penjualanbarang/update", array("id"=>$data->id))',
                                                        'options'=>array(
                                                            'class'=>'btn btn-success btn-xs',
                                                            'ajax'=>array(                                                                                    
                                                                'type'=>'POST',
                                                                'url'=>"js:$(this).attr('href')",
                                                                'beforeSend'=>'function(){  $("#AjaxLoader").show(); }',
                                                                'success'=>'function(data) {
                                                                        $("#AjaxLoader").hide();
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
    $('#Hygienispenjualanbarang_tanggal_index').datepicker();
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
