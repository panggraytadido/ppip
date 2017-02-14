<?php
$this->widget('booster.widgets.TbExtendedGridView', array(
    'type'=>'striped',
    'dataProvider' => $child,
    'template' => "{items}",
    'columns' =>
        array(
        	array(
                    'header'=>'No',    
                    'class'=>'IndexColumn',
                    'htmlOptions'=>array('width'=>'50px'),
                ),
             array(
                    'header'=>'Divisi',    
	            'name' => 'divisi',	          
	            'value' => '$data["divisi"]'
	        ),            	
			 array(
                    'header'=>'Nama Barang',
	            'name' => 'barang',	            
	            'value' => '$data["barang"]'
	        ),			
                array(
                    'header'=>'Jumlah',
	            'name' => 'jumlah',	            
	            'value' => '$data["jumlah"]'
	        ),
                array(
                    'header'=>'Harga Satuan',
	            'name' => 'hargasatuan',	            
	            'value' => 'number_format($data["hargasatuan"])'
	        ),
                array(
                    'header'=>'Harga Total',
	            'name' => 'hargatotal',	            
	            'value' => 'number_format($data["hargatotal"])'
	        ),
    ),
));