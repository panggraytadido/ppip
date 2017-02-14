<?php
$this->pageTitle = "Inbox - Kasir";

$this->breadcrumbs=array(
	'Kasir','Inbox','Data Barang Terjual'
);
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                 <div class="ibox-title">
                    <div class="pull-left">
                        <b>Kasir - Inbox</b>
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>
                    </div>
                </div>
                <div class="ibox-content">      
<?php $this->widget('booster.widgets.TbGridView', array(
	'id'=>'grid',
        'type' => 'striped bordered hover',
	'dataProvider'=>$model->listInbox(),
	'filter'=>$model,
        'afterAjaxUpdate' => 'reinstallDatePicker',   
        'responsiveTable'=>TRUE,
	'columns'=>array(
		array(
                    'header'=>'No',
                    'class'=>'IndexColumn',
                ),		
                array(
            				'class'=>'booster.widgets.TbRelationalColumn',
            				//'name' => 'firstLetter',
            				'header'=>'Detail Barang',
            				'url' =>$this->createUrl("inbox/childinbox"),
            				'value'=> '"<span class=\"fa fa-plus\"></span>"',
            				'htmlOptions'=>array('width'=>'100px'),            				
            				'type'=>'html',
            				'afterAjaxUpdate' => 'js:function(tr,rowid,data){
            		
                                    }'
            		),            
                array('name'=>'tanggal','value'=>'date("d-m-Y", strtotime($data->tanggal))','header'=>'Tanggal',
                        'filter'=>  $this->widget(
                            'booster.widgets.TbDatePicker',
                            array(
                                'name' => 'Inbox[tanggal]',
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
                array('name'=>'pelanggan','value'=>'$data->pelanggan','header'=>'Pelanggan',),		
		array(
							                    'header'=>'Action',
							                    'class'=>'booster.widgets.TbButtonColumn',
							                    'template'=>'{proses}  {delete}',
							                                    'buttons' => array(
							                                        'proses' =>   array(
							                                                       'label'=>'Proses',
																				   //'icon'=>'fa fa-print',
																					'url'=>'Yii::app()->createUrl("kasir/inbox/popupsetjenispembayaran", array("id"=>$data->id))', 							                                                       
							                                                        'options'=>array(
																						'class'=>'btn btn-success btn-xs',	
																						'ajax'=>array(                                                                                                                        
							                                                                'type'=>'POST',
							                                                            	//'dataType'=>'json',
							                                                                'url'=>"js:$(this).attr('href')",
							                                                                'success'=>'function(data) {							                                                            									                                                            									                                                            								                                                        
							                                                            		$("#modal-jenis-pembayaran .modal-body").html(data); 
							                                                            		$("#modal-jenis-pembayaran").modal();							                                                            		
																								}'
							                                                            ),	
							                                                        ),
							                                                    ),
																				
																				/*'jenispembayaran' =>   array(
																			   'label'=>'Set Jenis Pembayaran',
																			   'icon'=>'fa fa-pencil',
																				'url'=>'Yii::app()->createUrl("kasir/inbox/popupsetjenispembayaran", array("id"=>$data->id))', 							                                                       
							                                                        'options'=>array(
																						'class'=>'btn btn-success btn-xs',
							                                                            'ajax'=>array(                                                                                                                        
							                                                                'type'=>'POST',
							                                                            	//'dataType'=>'json',
							                                                                'url'=>"js:$(this).attr('href')",
							                                                                'success'=>'function(data) {							                                                            									                                                            									                                                            								                                                        
							                                                            		$("#modal-jenis-pembayaran .modal-body").html(data); 
							                                                            		$("#modal-jenis-pembayaran").modal();							                                                            		
																								}'
							                                                            ),
							                                                        ),
							                                                    ),*/
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
<!-- content -->
				 </div>
				</div>	              
                </div>
            </div>
        </div>

<?php
Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    $('#Inbox_tanggal').datepicker();
}
");
?>


<!-- modal update -->
<div class="modal fade" id="modal-jenis-pembayaran" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Set Jenis Pembayaran</h4>
        </div>
        <div class="modal-body">        
           <br>    
         
        </div>
          
        <div class="modal-footer">
          
        </div>

      </div>
    </div>
  </div>
<!--  -->