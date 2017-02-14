<?php
$this->pageTitle = "Data Kasbon - Keuangan";

$this->breadcrumbs=array(
	'Keuangan',
        'Data Kasbon',
);

?>

<div class="col-lg-12">
    <div id="AjaxLoader" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/themes/inspinia/img/loader.gif"></img></div>    
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">               
           <?php if(Yii::app()->user->hasFlash('success')):?>
                        <div class="alert alert-success fade in">
                            <button type="button" class="close close-sm" data-dismiss="alert">
                                <i class="fa fa-times"></i>
                            </button>
                            <?php echo Yii::app()->user->getFlash('success'); ?>
                        </div>    
                    <?php endif; ?>     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Keuangan - Data Kasbon</b>
                    </div>                   
                    <div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>
                        <?php 
                            echo CHtml::ajaxButton('Tambah',CHtml::normalizeUrl(array('datakasbon/create')),
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
                            'id'=>'grid',
                            'type' => 'striped bordered hover',
                            'dataProvider'=>$model->listKasbon(),
                            'filter'=>$model,                            
                            'columns'=>array(
                                    array(
                                        'header'=>'No',
                                        'class'=>'IndexColumn',
                                    ),                                     
                                    array('name'=>'tanggal','value'=>'date("d-m-Y", strtotime($data->tanggal))','header'=>'Tanggal Kasbon',
                                            'filter'=>  $this->widget(
                                                'booster.widgets.TbDatePicker',
                                                array(
                                                    'name' => 'Datakasbon[tanggal]',
                                                    'attribute' => 'tanggal',
                                                    'htmlOptions' => array(
                                                                        'id' => 'Datakasbon_tanggal_index',
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
                                    array('name'=>'karyawan','value'=>'$data->karyawan','header'=>'Karyawan'),                           
                                    array('name'=>'jumlah','value'=>'number_format($data->jumlah)','header'=>'Jumlah','filter'=>false),    
                                    array('name'=>'bayar',
                                        'value'=>function($data,$row)
                                        {
                                            $dataBayar = Datakasbon::model()->getTotalBayar($data->id); 
                                             if($dataBayar!='')
                                             {
                                                 return number_format($dataBayar);                                            
                                             }
                                             else
                                             {
                                                 return '0';
                                             }    
                                        },                                        
                                        'header'=>'Jumlah Bayar','filter'=>false),    
                                    array('name'=>'keterangan','value'=>'$data->keterangan','header'=>'Keterangan','filter'=>false),                                         
                                    array(
							                    'header'=>'Action',
							                    'class'=>'booster.widgets.TbButtonColumn',
							                    'template'=>'{update} {bayar} {delete}',
							                                    'buttons' => array(
							                                        'update' =>   array(
							                                                       'label'=>'Update',
                                                                                                               'icon'=>'fa fa-search-plus',
							                                                       'url'=>'Yii::app()->createUrl("keuangan/datakasbon/popupupdate", array("id"=>$data->id))', 							                                                       
							                                                        'options'=>array(
                                                                                                                    'class'=>'btn btn-success btn-xs',
							                                                            'ajax'=>array(
																							'beforeSend'=>'function(){  $("#AjaxLoader").show(); }',
							                                                                'type'=>'POST',
							                                                            	//'dataType'=>'json',
							                                                                'url'=>"js:$(this).attr('href')",
							                                                                'success'=>'function(data) {
							                                                            		$("#AjaxLoader").hide();									                                                            									                                                            								                                                        
							                                                            		$("#modal-update .modal-body").html(data); 
							                                                            		$("#modal-update").modal();
							                                                            		
																								}'
							                                                            ),
							                                                        ),
							                                                    ),
                                                                                                'bayar' =>   array(
							                                                       'label'=>'Bayar',
                                                                                                               'icon'=>'fa fa-search-plus',
							                                                       'url'=>'Yii::app()->createUrl("keuangan/datakasbon/popupbayar", array("id"=>$data->id))', 							                                                       
							                                                        'options'=>array(
                                                                                                                    'class'=>'btn btn-success btn-xs',
							                                                            'ajax'=>array(
																							'beforeSend'=>'function(){  $("#AjaxLoader").show(); }',
							                                                                'type'=>'POST',
							                                                            	//'dataType'=>'json',
							                                                                'url'=>"js:$(this).attr('href')",
							                                                                'success'=>'function(data) {
							                                                            		$("#AjaxLoader").hide();									                                                            									                                                            								                                                        
							                                                            		$("#modal-update .modal-body").html(data); 
							                                                            		$("#modal-update").modal();
							                                                            		
																								}'
							                                                            ),
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
                    )); ?>              
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
          <h4 class="modal-title" id="myModalLabel">Tambah Data Kasbon</h4>
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
<div class="modal fade" id="modal-update" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Bayar Kasbon</h4>
        </div>
        <div class="modal-body">        
           <br>    
         
        </div>
        <div class="modal-footer">
          <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save changes</button>-->
        </div>

      </div>
    </div>
  </div>
<!--  -->

<script>

</script>