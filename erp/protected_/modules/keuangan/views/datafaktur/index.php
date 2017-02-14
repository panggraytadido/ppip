<?php
$this->pageTitle = "Data Faktur - Keuangan";

$this->breadcrumbs=array(
	'Keuangan',
        'Data Faktur',
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
                        <b>Keuangan - Data Faktur</b>
                    </div>                   
                    <div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>                        
                    </div>
                </div>                                                
                <div class="ibox-content">
                       <?php $this->widget('booster.widgets.TbGridView', array(
                            'id'=>'grid',
                            'type' => 'striped bordered hover',
                            'dataProvider'=>$model->listFaktur(),
                            'filter'=>$model,                            
                            'columns'=>array(
                                    array(
                                        'header'=>'No',
                                        'class'=>'IndexColumn',
                                    ),                                     
                                    array('name'=>'nofaktur','value'=>'$data->nofaktur','header'=>'No. Faktur'),   
                                    array('name'=>'tanggal','value'=>'date("d-m-Y", strtotime($data->createddate))','header'=>'Tanggal Faktur',
                                            'filter'=>  $this->widget(
                                                'booster.widgets.TbDatePicker',
                                                array(
                                                    'name' => 'Datafaktur[tanggal]',
                                                    'attribute' => 'tanggal',
                                                    'htmlOptions' => array(
                                                                        'id' => 'Datafaktur_tanggal_index',
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
                                    array('name'=>'pelangganid','value'=>function($data,$row){
                                        return Pelanggan::model()->findByPk($data->pelangganid)->nama;
                                    },'header'=>'Pelanggan'),   
                                    array('name'=>'hargatotal','value'=>'number_format($data->hargatotal)','header'=>'Total','filter'=>false),                           
                                    array('name'=>'bayar','value'=>'number_format($data->bayar)','header'=>'Bayar','filter'=>false),    
                                    array('name'=>'sisa',
                                        'value'=>'$data->sisa',
                                        'header'=>'Sisa','filter'=>false),    
                                    //array('name'=>'keterangan','value'=>'$data->keterangan','header'=>'Keterangan'),                                         
                                    array(
							                    'header'=>'Action',
							                    'class'=>'booster.widgets.TbButtonColumn',
							                    'template'=>'{print} {delete}',
							                                    'buttons' => array(
							                                        'print' =>   array(
							                                                       'label'=>'Print Faktur',
                                                                                                               'icon'=>'fa fa-print',
                                                                                                               // 'url'=>'Yii::app()->createUrl("kasir/datasetoran/cetak", array("id"=>$data->id))', 
                                                                                                               'url'=>'Yii::app()->createUrl("keuangan/datafaktur/checkfaktur", array("pelangganid"=>$data->pelangganid,"tanggal"=>date("d-m-Y", strtotime($data->tanggal)),"nofaktur"=>$data->nofaktur))', 
							                                                        'options'=>array(
																					
                                                                                                                    'class'=>'btn btn-success btn-xs',							                                                           
                                                                                                                    //'onclick'=>'cetakFaktur(this);',
                                                                                                                    'ajax'=>array(   
																							'beforeSend'=>'function(){  $("#AjaxLoader").show(); }',							
							                                                                'type'=>'POST',
							                                                            	'dataType'=>'json',
							                                                                'url'=>"js:$(this).attr('href')",
							                                                                'success'=>'js:function(data) {
																										$("#AjaxLoader").hide();	
                                                                                                                            if(data.status==1)
                                                                                                                            {
                                                                                                                                //location.href="'.Yii::app()->baseurl.'/keuangan/datafaktur/cetakfaktur/pelangganid/" + data.pelangganid+"/tanggal/" +data.tanggal+"/nofaktur/"+data.nofaktur;
																																window.open("'.Yii::app()->baseurl.'/keuangan/datafaktur/cetakfaktur/pelangganid/" + data.pelangganid+"/tanggal/" +data.tanggal+"/nofaktur/"+data.nofaktur, "_blank");  
                                                                                                                            }
                                                                                                                            else 
                                                                                                                            {
                                                                                                                                location.href="'.Yii::app()->baseurl.'/kasir/datasetoran/formfaktur/pelangganid/" + data.pelangganid+"/tanggal/" +data.tanggal
                                                                                                                            }
                                                                                                                            								
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
							                                                                'type'=>'POST',
							                                                            	//'dataType'=>'json',
							                                                                'url'=>"js:$(this).attr('href')",
							                                                                'success'=>'function(data) {
							                                                            									                                                            									                                                            								                                                        
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
          <h4 class="modal-title" id="myModalLabel">Update Data Kasbon</h4>
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