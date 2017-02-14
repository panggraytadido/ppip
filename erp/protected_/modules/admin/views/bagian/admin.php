<?php
/* @var $this BagianController */
/* @var $model Bagian */

$this->breadcrumbs=array(
	'Bagian'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Bagian', 'url'=>array('index')),
	array('label'=>'Create Bagian', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#bagian-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Data Bagian</h1>


<?php $this->widget('booster.widgets.TbExtendedGridView', array(
	'id'=>'bagian-grid',
        'type' => 'striped bordered hover',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'columns'=>array(
		array(
                    'header'=>'No',
                    'class'=>'IndexColumn',
                ),
		'divisiid',
		'kode',
		'nama',
		'keterangan',
		array(
							                    'header'=>'Action',
							                    'class'=>'booster.widgets.TbButtonColumn',
							                    'template'=>'{d}',
							                                    'buttons' => array(
							                                        'd' =>   array(
							                                                       'label'=>'',
							                                                       'url'=>'Yii::app()->createUrl("admin/bagian/admin/popupupdate", array("id"=>$data->id))', 							                                                       
							                                                        'options'=>array(
                                                                                                                    'class'=>'btn btn-success btn-xs fa fa-search-plus',
							                                                            'ajax'=>array(                                                                                                                        
							                                                                'type'=>'POST',
							                                                            	'dataType'=>'json',
							                                                                'url'=>"js:$(this).attr('href')",
							                                                                'success'=>'function(data) {
							                                                            									                                                            									                                                            								                                                        
							                                                            		$("#viewModal .modal-body p").html(data); 
							                                                            		$("#viewModal").modal();
							                                                            		
																								}'
							                                                            ),
							                                                        ),
							                                                    ),
							                                    ),
							            ),
	),
)); ?>

 <!-- View Popup Item Detail -->
		<?php $this->beginWidget('booster.widgets.TbModal', array('id'=>'viewModal')); ?>
		<div class="modal-header">
		<h4>Item Detail</h4>
		</div>
		<div class="modal-body">
		<p>Item Detail</p>
		</div>
		<?php $this->endWidget(); ?>
		<!-- View Popup ends -->