<?php
/* @var $this BagianController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Pelanggan',
);

Yii::app()->clientScript->registerScript('search', "

$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('bagian-grid', {
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

<script>
    function refreshGridView()
    {
        $.fn.yiiGridView.update("pelanggan-grid");
    }
</script>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Gudang - Pelanggan</b>
                    </div>
                    <div class="pull-right">
                        <div style="float:right;"><a href="#" onclick="refreshGridView();" class="btn btn-info btn-xs"> Refresh</a> <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#modal-bagian"><i class="fa fa-plus"></i>Tambah</button></div> 
                    </div>
                </div>
                <div class="ibox-content">                               

<?php $this->widget('booster.widgets.TbGridView', array(
	'id'=>'pelanggan-grid',
        'type' => 'striped bordered hover',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array(
                    'header'=>'No',
                    'class'=>'IndexColumn',
                ),		
		'kode',
		'nama',                
               // array('name'=>'propinsiid','value'=>'$data->propinsi->nama','header'=>'Propinsi'),
                //array('name'=>'kotaid','value'=>'$data->kota->nama','header'=>'Kota'),		
                'status',
		array(
							                    'header'=>'Action',
							                    'class'=>'booster.widgets.TbButtonColumn',
							                    'template'=>'{update} {delete}',
							                                    'buttons' => array(
							                                        'update' =>   array(
							                                                       'label'=>'',
                                                                                                               'icon'=>'fa fa-search-plus',
							                                                       'url'=>'Yii::app()->createUrl("admin/pelanggan/popupupdate", array("id"=>$data->id))', 							                                                       
							                                                        'options'=>array(
                                                                                                                    'class'=>'btn btn-success btn-xs',
							                                                            'ajax'=>array(                                                                                                                        
							                                                                'type'=>'POST',
							                                                            	//'dataType'=>'json',
							                                                                'url'=>"js:$(this).attr('href')",
							                                                                'success'=>'function(data) {
							                                                            									                                                            									                                                            								                                                        
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
</div>  <!-- content -->
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
          <h4 class="modal-title" id="myModalLabel">Tambah Data Pelanggan</h4>
        </div>
        <div class="modal-body">
           <div id="AjaxLoader" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/themes/gentelala/images/ajax-loader.gif"></img></div>
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
          <h4 class="modal-title" id="myModalLabel">Update Data Pelanggan</h4>
        </div>
        <div class="modal-body">
        <div id="AjaxLoaderUpdate" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/themes/gentelala/images/ajax-loader.gif"></img></div>
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