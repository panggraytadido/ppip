<?php
$this->pageTitle = "Tabungan Karyawan - Keuangan";

$this->breadcrumbs=array(
	'Keuangan',
        'Data Tabungan Karyawan',
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
                        <b>Keuangan - Data Tabungan</b>
                    </div>                   
                    <div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>
                        <?php 
                            echo CHtml::ajaxButton('Tambah',CHtml::normalizeUrl(array('datatabungan/create')),
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
                            'dataProvider'=>$model->listTabungan(),
                            'filter'=>$model,                            
                            'columns'=>array(
                                    array(
                                        'header'=>'No',
                                        'class'=>'IndexColumn',
                                    ),
                                    array(
                                            'class'=>'booster.widgets.TbRelationalColumn',
                                            //'name' => 'firstLetter',
                                            'header'=>'Detail',
                                            'url' =>$this->createUrl("datatabungan/detail"),
                                            'value'=> '"<span class=\"fa fa-plus\"></span>"',
                                            'htmlOptions'=>array('width'=>'100px'),            				
                                            'type'=>'html',
                                            'afterAjaxUpdate' => 'js:function(tr,rowid,data){
            		
                                            }'
                                        ),
							 array('name'=>'pelangganid','value'=>function($data,$row){
								 return Pelanggan::model()->findByPk($data->pelangganid)->nama;
							 },'header'=>'Pelanggan','filter' => CHtml::listData(Pelanggan::model()->findAll(), 'id', 'nama')),            			
                             array('name'=>'jumlah','value'=>'number_format($data->jumlah)','header'=>'Jumlah','filter'=>false), 
                             array(
							                    'header'=>'Action',
							                    'class'=>'booster.widgets.TbButtonColumn',
							                    'template'=>'{ambil}',
							                                    'buttons' => array(
							                                        'ambil' =>   array(
							                                                       'label'=>'Ambil Tabungan',
                                                                                                               'icon'=>'fa fa-search-plus',
							                                                       'url'=>'Yii::app()->createUrl("keuangan/datatabungan/popupambiltabungan", array("id"=>$data->id))', 							                                                       
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
          <h4 class="modal-title" id="myModalLabel">Tambah Data Tabungan</h4>
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


<!-- modal create -->
<div class="modal fade bs-example-modal-sm" id="modal-update" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Ambil Tabungan</h4>
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