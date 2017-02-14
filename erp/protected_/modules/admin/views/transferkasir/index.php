<?php
$this->pageTitle = "Transfer - Transfer Kasir";

$this->breadcrumbs=array(
	'Keuangan',
        'Transfer Kasir',
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
                        <b>Transfer Kasir</b>
                    </div>
                    
                    <div class="pull-right">            
						<a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>
						<?php echo CHtml::link('<li class="fa fa-mail-reply"></li> Kembali',array('../admin/datatransfer'), array('class'=>'btn btn-default btn-xs')); ?>                          
                        <?php 
                            echo CHtml::ajaxButton('Tambah',CHtml::normalizeUrl(array('transferkasir/create')),
                                array(
                                    'type'=>'POST',                      
                                    'url'=>"$(this).attr('href')",
                                    'beforeSend'=>'function(){  $("#AjaxLoader").show(); }',
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
                            'dataProvider'=>$model->search(),
                            'filter'=>$model,                         
							'afterAjaxUpdate' => 'reinstallDatePicker',   	
                            'columns'=>array(
                                    array(
                                        'header'=>'No',
                                        'class'=>'IndexColumn',
                                    ),
                             array('name'=>'tanggal','value'=>'date("d-m-Y", strtotime($data->tanggal))','header'=>'Tanggal','filter'=>  $this->widget(
                                                'booster.widgets.TbDatePicker',
                                                array(
                                                    'name' => 'Transferkasir[tanggal]',
                                                    'attribute' => 'tanggal',
                                                    'htmlOptions' => array(
                                                                        'id' => 'Transferkasir_tanggal_index',
                                                                        'class'=>'form-control ct-form-control',
                                                                    ),
                                                    'options' => array(
                                                        'format' => 'yyyy-mm-dd',
                                                        'language' => 'en',
                                                        'class'=>'form-controls form-control-inline input-medium default-date-picker',
                                                        'size'=>"16"
                                                    )
                                                ),true
                                            )), 
                             array('name'=>'tanggal','value'=>'$data->rekening->norekening','header'=>'No Rekening','filter'=>false),    
                             array('name'=>'Bank','value'=>'$data->rekening->namabank','header'=>'Bank','filter'=>false),       
                             array('name'=>'Nama Pemilik','value'=>'$data->rekening->namapemilik','header'=>'Nama Pemilik','filter'=>false), 
                             array('name'=>'jumlah','value'=>'number_format($data->jumlah)','header'=>'Jumlah','filter'=>false),  
                             array(
							                    'header'=>'Action',
							                    'class'=>'booster.widgets.TbButtonColumn',
							                    'template'=>'{update}',
							                                    'buttons' => array(
							                                        'update' =>   array(
							                                                       'label'=>'Ubah',
																				   'icon'=>'fa fa-pencil',
							                                                       'url'=>'Yii::app()->createUrl("admin/transferkasir/update", array("id"=>$data->id))', 							                                                       
							                                                        'options'=>array(
                                                                                                                    'class'=>'btn btn-success btn-xs',
							                                                            'ajax'=>array(                                                                                                                       
							                                                                'type'=>'POST',
							                                                            	//'dataType'=>'json',
							                                                                'url'=>"js:$(this).attr('href')",
																							'beforeSend'=>'function()
																							{  
																								$("#AjaxLoader").show(); 
																							}',
							                                                                'success'=>'function(data) {
							                                                            		$("#AjaxLoader").hide();							                                                            									                                                            								                                          
							                                                            		$("#modal-update .modal-body").html(data); 
							                                                            		$("#modal-update").modal();																								
							                                                            		
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
    $('#Transferkasir_tanggal_index').datepicker();
}
");
?>


<div class="modal fade bs-example-modal-sm" id="modal-create" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Transfer Kasir</h4>
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
          <h4 class="modal-title" id="myModalLabel">Update Data Transfer Kasir</h4>
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