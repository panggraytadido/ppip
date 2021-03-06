<?php
/* @var $this BagianController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = 'Admin - Anggota';

$this->breadcrumbs=array(
	'Anggota',
);

Yii::app()->clientScript->registerScript('search', "

$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('jabatan-grid', {
        data: $(this).serialize()
    });
    return false;
});
");

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
                <div class="ibox-content">       
                    <div style="float:right;">
					 <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>
					<?php 
                            echo CHtml::ajaxButton('Tambah',CHtml::normalizeUrl(array('anggota/create')),
                                array(
                                    'type'=>'POST',                      
                                    'url'=>"$(this).attr('href')",
                                    'beforeSend'=>'function()
                                    {                        
                                        $("#AjaxLoader").show();
                                    }',
                                    'success'=>'js:function(data) 
                                     {    
                                        $("#AjaxLoader").hide();
                                        $("#modal-create #body-create").html(data); 
                                        $("#modal-create").modal({backdrop: "static", keyboard: false});
                                    }'               
                                ),array('id'=>'btnCreate','class'=>'btn btn-primary btn-xs'));
                        ?> </div>
        <?php $this->widget('booster.widgets.TbGridView', array(
	'id'=>'jabatan-grid',
         'responsiveTable' => true,  
        'type' => 'striped bordered hover',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
                    'header'=>'No',
                    'class'=>'IndexColumn',
                ),		
		array('name'=>'kode','header'=>'Kode Anggota','value'=>'$data->kode','filter'=>false),	
		'nama',
		array('name'=>'alamat','header'=>'Alamat','value'=>'$data->alamat','filter'=>false),	
		array('name'=>'jeniskelamin','header'=>'Jenis Kelamin',
		   'value'=>function($data,$row){
				if($data->jeniskelamin==1)
				{
					return 'Pria';
				}
				else 
				{
					return 'Wanita';
				}
			},'type'=>'Raw','filter'=>false),		
		array('name'=>'hp','header'=>'HP','value'=>'$data->hp','filter'=>false),	
		array(
			 'header'=>'Photo KTP',
			'type' => 'raw',
			'value'=>function($data,$row){
				if($data->photoktp!="")
				{
					return CHtml::image(Yii::app()->baseUrl . "/../upload/" . $data->photoktp,"",array("style"=>"width:50px;height:50px;"));
				}
				else
				{
					return 'tidak ada photo ktp';
				}
			}                                                               
		),               
		array(
							                    'header'=>'Action',
							                    'class'=>'booster.widgets.TbButtonColumn',
							                    'template'=>'{update} {delete}',
							                                    'buttons' => array(
							                                        'update' =>   array(
							                                                       'label'=>'',
                                                                                                               'icon'=>'fa fa-search-plus',
							                                                       'url'=>'Yii::app()->createUrl("admin/anggota/popupupdate", array("id"=>$data->id))', 							                                                       
							                                                        'options'=>array(
                                                                                                                    'class'=>'btn btn-success btn-xs',
							                                                            'ajax'=>array(                                                                                                                        
							                                                                'type'=>'POST',
							                                                            	//'dataType'=>'json',
							                                                                'url'=>"js:$(this).attr('href')",
                                                                                                                        'beforeSend'=>'function(){  $("#AjaxLoader").show(); }',
							                                                                'success'=>'function(data) {
							                                                            		$("#AjaxLoader").hide();								                                                            									                                                            								                                                        
							                                                            		$("#modal-update-karyawan .modal-body").html(data); 
							                                                            		$("#modal-update-karyawan").modal();
							                                                            		
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
   


<!-- modal add -->
<div class="modal fade bs-example-modal-sm" id="modal-create" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Tambah Data Anggota</h4>
        </div>
        <div class="modal-body" id="body-create">
           <div id="AjaxLoader" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/themes/inspinia/img/loader.gif"></img></div>
           <br>
         <?php //$this->renderPartial('_form', array('model'=>$model)); ?>
        </div>
        <div class="modal-footer">
          <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
          <!--<button type="button" class="btn btn-primary">Save changes</button>-->
        </div>

      </div>
    </div>
  </div>
<!-- -->

<div class="modal fade bs-example-modal-sm" id="modal-update-karyawan" tabindex="-1" role="dialog"  aria-hidden="true">
<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title">Update Data Anggota</h4>            
        </div>
        <div class="modal-body">
            <div id="AjaxLoader" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/themes/inspinia/img/loader.gif"></img></div>
           <br>
         
        </div>

        <div class="modal-footer">
            <!-- <button type="button" class="btn btn-white" data-dismiss="modal">Close</button> 
            <button type="button" class="btn btn-primary">Save changes</button>-->
        </div>
    </div>
</div>
</div>

