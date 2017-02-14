<?php
/* @var $this BagianController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Barang',
);

Yii::app()->clientScript->registerScript('search', "

$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('grid', {
        data: $(this).serialize()
    });
    return false;
});
");

?>

<div class="dashboard_graph">
    <h4>Data Barang</h4>
</div>
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

<div class="dashboard_graph">
    <div class="search-form">
        <?php $this->renderPartial("_search",array("model"=>$model));?>
    </div>
</div>
<br>

<div class="dashboard_graph">    
<div style="float:right;"><button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-bagian">Add</button></div>


<?php $this->widget('booster.widgets.TbGridView', array(
	'id'=>'grid',
        'type' => 'striped bordered hover',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		array(
                    'header'=>'No',
                    'class'=>'IndexColumn',
                ),
		array('name'=>'divisiid','value'=>'$data->divisi->nama','header'=>'Divisi'),
		'kode',
		'nama',
		array('name'=>'hargamodal','value'=>'number_format($data->hargamodal)','header'=>'Harga Modal'),
                array('name'=>'hargaeceran','value'=>'number_format($data->hargaeceran)','header'=>'Harga Eceran'),
                array('name'=>'hargagrosir','value'=>'number_format($data->hargagrosir)','header'=>'Harga Grosir'),
		array(
							                    'header'=>'Action',
							                    'class'=>'booster.widgets.TbButtonColumn',
							                    'template'=>'{update} {delete}',
							                                    'buttons' => array(
							                                        'update' =>   array(
							                                                       'label'=>'',
                                                                                                               'icon'=>'fa fa-search-plus',
							                                                       'url'=>'Yii::app()->createUrl("admin/barang/popupupdate", array("id"=>$data->id))', 							                                                       
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

<!-- modal add -->
<div class="modal fade bs-example-modal-sm" id="modal-bagian" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Tambah Data Barang</h4>
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
          <h4 class="modal-title" id="myModalLabel">Update Data Bagian</h4>
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