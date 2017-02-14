<?php
$this->pageTitle = "Karyawan Bulanan - Keuangan";

$this->breadcrumbs=array(
	'Keuangan',
        'Karyawan Bulanan',
);

?>

<div class="col-lg-12">
    <div id="AjaxLoader" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/themes/inspinia/img/loader.gif"></img></div>    
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Keuangan - Karyawan Bulanan</b>
                    </div> 

					<div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>
                        <?php 
                            echo CHtml::ajaxButton('Tambah',CHtml::normalizeUrl(array('karyawanbulanan/create')),
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
                       <?php 
$this->widget('booster.widgets.TbGridView', array(
	'id'=>'hygienispenerimaanbarang-grid',
        'type' => 'striped bordered hover',
	'dataProvider'=>$model->searchKaryawanbulanan(),
	'filter'=>$model,
        //'afterAjaxUpdate' => 'reinstallDatePicker', 
	'columns'=>array(
		array(
                    'header'=>'No',
                    'class'=>'IndexColumn',
                ),
                 array('name'=>'nik','value'=>'$data->nik','header'=>'NIK'),                                
				array('name'=>'karyawan','value'=>'$data->namakaryawan','header'=>'Karyawan'),                           
                array('name'=>'gaji','value'=>'number_format($data->gaji)','header'=>'Gaji','filter'=>false),       
				array('name'=>'jabatanid','value'=>function($data,$row){return Jabatan::model()->findByPk($data->jabatanid)->nama;},'header'=>'Jabatan','filter'=>false),       	
				array(
                    'header'=>'Action',
                    'class'=>'booster.widgets.TbButtonColumn',
                    'template'=>'{update}',
                                    'buttons' => array(                                     
                                        'update' =>   array(
                                                       'label'=>'edit',
                                                       'icon'=>'fa fa-edit',
                                                       'url'=>'Yii::app()->createUrl("keuangan/karyawanbulanan/popupupdate", array("id"=>$data->id))',
                                                        'options'=>array(
                                                            'class'=>'btn btn-success btn-xs',
                                                            'ajax'=>array(                         
																'beforeSend'=>'function(){  $("#AjaxLoader").show(); }',
                                                                'type'=>'POST',
                                                                'url'=>"js:$(this).attr('href')",
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
)); 
?>
                </div>
            </div>
        </div>
    </div>
   
</div>

<!-- modal create -->
<div class="modal fade bs-example-modal-sm" id="modal-create" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Tambah Gaji Karyawan Bulanan</h4>
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
          <h4 class="modal-title" id="myModalLabel">Update Gaji Karyawan Bulanan</h4>
        </div>
        <div class="modal-body" id="body-update">
             
        </div>
        <div class="modal-footer">
          &nbsp;
        </div>
      </div>
    </div>
  </div>
<!-- -->
