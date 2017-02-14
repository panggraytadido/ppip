<?php
$this->pageTitle = "Stock Update - Besek";

$this->breadcrumbs=array(
	'Besek',
        'Stock Update',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Besek - Stock Update</b>
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>
                    </div>
                </div>
                
                <div class="ibox-content">

                    <?php if(Yii::app()->user->hasFlash('success')):?>
                            <div class="alert alert-success fade in">
                                <button type="button" class="close close-sm" data-dismiss="alert">
                                    <i class="fa fa-times"></i>
                                </button>
                                <?php echo Yii::app()->user->getFlash('success'); ?>
                            </div>    
                    <?php endif; ?>


<?php $this->widget('booster.widgets.TbGridView', array(
	'id'=>'stockupdate-grid',
        'type' => 'striped bordered hover',
        'responsiveTable'=>true,
	'dataProvider'=>$model->searchStockupdate(),
	'filter'=>$model,
	'columns'=>array(
		array(
                    'header'=>'No',
                    'class'=>'IndexColumn',
                ),		
                array(
            				'class'=>'booster.widgets.TbRelationalColumn',
            				//'name' => 'firstLetter',
            				'header'=>'Detail Barang',
            				'url' =>$this->createUrl("stockupdate/detail"),
            				'value'=> '"<span class=\"fa fa-plus\"></span>"',
            				'htmlOptions'=>array('width'=>'100px'),            				
            				'type'=>'html',
            				'afterAjaxUpdate' => 'js:function(tr,rowid,data){
            		
            }'
            		),
		array('name'=>'kode','value'=>'$data->kode','header'=>'Kode Barang'),
		array('name'=>'nama','value'=>'$data->nama','header'=>'Nama Barang'),
                array('name'=>'namalokasi','value'=>'$data->namalokasi','header'=>'Lokasi'),
                array('name'=>'jumlah','value'=>'$data->jumlah','header'=>'Jumlah','filter'=>''),
	),
)); ?>

                </div>
            </div>	              
        </div>
    </div>
</div>