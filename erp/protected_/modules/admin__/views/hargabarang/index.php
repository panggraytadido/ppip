<?php
$this->pageTitle = "Admin - Harga Barang";

$this->breadcrumbs=array(
	'Harga Barang',
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

<div class="col-lg-12">
    <div id="AjaxLoader" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/themes/inspinia/img/loader.gif"></img></div>    
</div>


<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
				<div class="ibox-title">
                    <div class="pull-left">
                        <b>Admin - Harga Barang</b>
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>
						<?php 
								echo CHtml::link('Print',array('hargabarang/print'), array('target'=>'_blank','class'=>'btn btn-warning btn-xs')); 
							?>
                    </div>
                </div>
                <div class="ibox-content"> 
					<?php $this->renderPartial('grid_hargabarang',array('model'=>$model)); ?>
				</div>
   
                    <br>    
 </div>
	</div>	              
	</div>
</div>

