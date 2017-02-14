<?php
$this->pageTitle = "Admin - Data Beli Barang";

$this->breadcrumbs=array(
	'Admin','Data Beli Barang'
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
                        <b>Admin - Data Beli Barang</b>
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>
                        <?php echo CHtml::link('<li class="fa fa-mail-reply"></li> Kembali',array('../admin/datatransfer'), array('class'=>'btn btn-default btn-xs')); ?>
                        <?php 
                            echo CHtml::ajaxButton('Tambah',CHtml::normalizeUrl(array('transferkeluar/create')),
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
	'dataProvider'=>$model->search(),
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
            				'header'=>'Detail',
            				'url' =>$this->createUrl("transferkeluar/detail"),
            				'value'=> '"<span class=\"fa fa-plus\"></span>"',
            				'htmlOptions'=>array('width'=>'100px'),            				
            				'type'=>'html',
            				'afterAjaxUpdate' => 'js:function(tr,rowid,data){
            		
                                    }'
            		),                       
                array('name'=>'namaperusahaan','value'=>'$data->namaperusahaan','header'=>'Supplier',),	
				array('name'=>'namaperusahaan','value'=>function($data,$row){
					$connection=Yii::app()->db;
					$supplierid =$data->id;
					$sql ="select max(id) as id from transaksi.transfer 
							group by supplierid,isdeleted
								having supplierid=$supplierid and isdeleted=false
									limit 1";           
										
					$data=$connection->createCommand($sql)->queryRow();
					$id = $data["id"];                        
					if($id!="")
					{
						$saldo = Transfer::model()->findByPk($data["id"])->saldo;												
						if($saldo!="" || $saldo!=0)
						{
							return number_format($saldo);
						}   	
						else
						{
							return 0;
						}		
					}		
					else
					{
						return 0;
					}
				},'header'=>'Saldo','filter'=>false),	
		array(
							                    'header'=>'Action',
							                    'class'=>'booster.widgets.TbButtonColumn',
							                    'template'=>'{update} {jenispembayaran} {delete}',
							                                    'buttons' => array(
							                                        'update' =>   array(
							                                                       'label'=>'Print Faktur',
                                                                                                               'icon'=>'fa fa-print',
                                                                                                                'url'=>'Yii::app()->createUrl("kasir/inbox/formfaktur", array("id"=>$data->id))', 							                                                       
							                                                        'options'=>array(
                                                                                                                    'class'=>'btn btn-success btn-xs',							                                                          
							                                                        ),
							                                                    ),
                                                                                                'jenispembayaran' =>   array(
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
<div class="modal fade" id="modal-create" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Transfer Keluar</h4>
        </div>
        <div class="modal-body" id="body-create">        
           <br>    
         
        </div>
          
        <div class="modal-footer">
          
        </div>

      </div>
    </div>
  </div>
<!--  -->