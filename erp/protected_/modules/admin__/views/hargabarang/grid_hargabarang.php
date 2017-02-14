
<?php $this->widget('booster.widgets.TbGridView', array(
	'id'=>'gudangpenerimaanbarang-grid',
        'type' => 'striped bordered hover',
        'responsiveTable'=>true,
	'dataProvider'=>$model->listHargaBarang(),
	'filter'=>$model,
        //'afterAjaxUpdate' => 'reinstallDatePicker', 
	'columns'=>array(
		array(
                    'header'=>'No',
                    'class'=>'IndexColumn',
                ),		
		array('name'=>'divisi','value'=>'$data->divisi','header'=>'Divisi'),		
		array('name'=>'supplier','value'=>'$data->supplier','header'=>'Supplier'),
		array('name'=>'barang','value'=>'$data->barang','header'=>'Nama Barang'),      
		array('name'=>'hargamodal','value'=>'number_format($data->hargamodal)','header'=>'Harga Modal','filter'=>false),      	
		array('name'=>'hargagrosir','value'=>'number_format($data->hargagrosir)','header'=>'Harga Grosir','filter'=>false),      	
		array('name'=>'hargaeceran','value'=>'number_format($data->hargaeceran)','header'=>'Harga Eceran','filter'=>false),      		
	),
)); ?>