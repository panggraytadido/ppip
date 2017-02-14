
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
                    'header'=>'Jenis',    
	            'name' => 'jenistransfer',	          
                    'value' =>'$data["jenistransfer"]'
	        ),
           /* array(
                    'header'=>'Bank',    
	            'name' => 'rekeningid',	          
                    'value' => function($data,$row){
                        return Rekening::model()->findByPk($data["rekeningid"])->namabank;
                    }
	        ),    
            array(
                    'header'=>'No. Rek',    
	            'name' => 'rekeningid',	          
                    'value' => function($data,$row){
                        return Rekening::model()->findByPk($data["rekeningid"])->norekening;
                    }
	        ),
            * 
            */
             array(
                    'header'=>'Barang',    
	            //'name' => 'rekeningid',	          
                    'value' =>'$data["barang"]'
	        ),      
             array(
                    'header'=>'Beli',    
	            'name' => 'beli',	          
                    'value' =>'number_format($data["beli"])'
	        ), 
             array(
                    'header'=>'Jual',    
	            'name' => 'jual',	          
                    'value' =>'number_format($data["jual"])'
	        ), 
             array(
                    'header'=>'Harga',    
	            'name' => 'harga',	          
                    'value' =>'number_format($data["harga"])'
	        ),             
            array(
                    'header'=>'Debit',    
	            'name' => 'debit',	          
                    'value' =>'number_format($data["debit"])'
	        ), 
            array(
                    'header'=>'Kredit',    
	            'name' => 'kredit',	          
                    'value' =>'number_format($data["kredit"])'
	        ),       
            array(
                    'header'=>'Saldo',    
	            'name' => 'saldo',	          
                    'value' =>'number_format($data["saldo"])'
	        ),             
    ),
));
?>

<br>
<br>
<br>