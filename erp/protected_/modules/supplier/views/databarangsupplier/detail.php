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
                    'header'=>'Barang',
	            'name' => 'barang',	            
                    'value' =>'$data["barang"]'
	        ),			
                array(
                    'header'=>'Lokasi',
	            'name' => 'lokasi',	            
	            'value' => '$data["lokasi"]'
	        ),
                array(
                    'header'=>'Jumlah Stock',
	            'name' => 'stock',	            
	            'value' => '$data["stock"]'
	        ),                      
                        
               
    ),
));