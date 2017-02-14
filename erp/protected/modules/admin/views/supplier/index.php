<?php

$this->pageTitle = "Supplier - Admin";

$this->breadcrumbs=array(
	'Supplier',
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
<div style="float:right;"><button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-bagian"><i class="fa fa-plus"></i>Tambah</button></div>


<?php $this->widget('booster.widgets.TbGridView', array(
	'id'=>'grid',
        'type' => 'striped bordered hover',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
                    'header'=>'No',
                    'class'=>'IndexColumn',
                ),				
		array('name'=>'namaperusahaan','value'=>'$data->namaperusahaan','header'=>'Supplier'),	
                array('name'=>'namapemilik','value'=>function($data,$row)
                    {
                        if($data->namapemilik!='')
                        {
                            return $data->namapemilik;
                        }
                        else
                        {
                            return '-';
                        }
                    },'header'=>'Nama Pemilik'),
                array('name'=>'hp','header'=>'HP','value'=>function($data,$row)
                {
                    if($data->hp!='')
                    {
                        return $data->hp;
                    }
                    else
                    {
                        echo '-';
                    }    
                },'filter'=>false
                    ),
                array('name'=>'fax','header'=>'FAX','value'=>function($data,$row)
                {
                    if($data->fax!='')
                    {
                        return $data->fax;
                    }
                    else
                    {
                        echo '-';
                    }    
                },'filter'=>false
                    ),  
                array('name'=>'hp','header'=>'HP','value'=>function($data,$row)
                {
                    if($data->hp!='')
                    {
                        return $data->hp;
                    }
                    else
                    {
                        echo '-';
                    }    
                },'filter'=>false
                    ),             
                //'alamat',		
		array(
							                    'header'=>'Action',
							                    'class'=>'booster.widgets.TbButtonColumn',
							                    'template'=>'{update} {delete}',
							                                    'buttons' => array(
							                                        'update' =>   array(
							                                                       'label'=>'',
                                                                                                               'icon'=>'fa fa-search-plus',
							                                                       'url'=>'Yii::app()->createUrl("admin/supplier/popupupdate", array("id"=>$data->id))', 							                                                       
							                                                        'options'=>array(
                                                                                                                    'class'=>'btn btn-success btn-xs',
							                                                            'ajax'=>array(                                                                                                                        
							                                                                'type'=>'POST',
							                                                            	//'dataType'=>'json',
							                                                                'url'=>"js:$(this).attr('href')",
                                                                                                                        'beforeSend'=>'function(){  $("#AjaxLoader").show(); }',
							                                                                'success'=>'function(data) {
							                                                            		$("#AjaxLoader").hide();								                                                            									                                                            								                                                        
							                                                            		$("#modal-update-bagian .modal-body").html(data); 
							                                                            		$("#modal-update-bagian").modal();
							                                                            		
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


<!-- modal add -->
<div class="modal fade bs-example-modal-sm" id="modal-bagian" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Tambah Data Supplier</h4>
        </div>
        <div class="modal-body">
           <div id="AjaxLoader" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/themes/inspinia/img/loader.gif"></img></div>           
           <br>
         <?php $this->renderPartial('_form', array('model'=>$model)); ?>
        </div>
        <div class="modal-footer">
          <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
          <!--<button type="button" class="btn btn-primary">Save changes</button>-->
        </div>

      </div>
    </div>
  </div>
<!-- -->

<!-- modal update -->
<div class="modal fade" id="modal-update-bagian" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Update Data Supplier</h4>
        </div>
        <div class="modal-body">
        <div id="AjaxLoader" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/themes/inspinia/img/loader.gif"></img></div>           
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