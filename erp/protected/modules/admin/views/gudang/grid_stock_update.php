<?php 
$this->widget('booster.widgets.TbGridView', array(
		'id'=>'grid',
		'type' => 'striped bordered hover',
		'dataProvider'=>$modelStockupdate->listStockUpdate(),
		'filter'=>$modelStockupdate,                                    
		'columns'=>array(
				array(
					'header'=>'No',
					'class'=>'IndexColumn',
					'htmlOptions'=>array('width'=>'5px'),
				),     
			 array(
								'class'=>'booster.widgets.TbRelationalColumn',
								//'name' => 'firstLetter',
								'header'=>'Detail Barang',
								'url' =>$this->createUrl("gudang/detailstockupdate"),
								'value'=> '"<span class=\"fa fa-plus\"></span>"',
								'htmlOptions'=>array('width'=>'100px'),            				
								'type'=>'html',
								'afterAjaxUpdate' => 'js:function(tr,rowid,data){
						
				}'
						),
				array('name'=>'barang','value'=>function($data,$row){
				return Barang::model()->findByPk($data->id)->nama;
				},'header'=>'Barang'), 											
				array('name'=>'totalstock','value'=>'$data->totalstock','header'=>'Total Stock','filter'=>false), 
				//array('name'=>'harga','value'=>'number_format($data->harga)','header'=>'Harga','filter'=>false),
				//array('name'=>'total','value'=>'number_format($data->total)','header'=>'Total','filter'=>false),
		),

)); 
    ?>

