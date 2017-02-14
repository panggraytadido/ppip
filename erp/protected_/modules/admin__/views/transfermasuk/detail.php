<?php
$this->widget('booster.widgets.TbExtendedGridView', array(
    'type'=>'striped',
    'dataProvider' => $child,
    'template' => "{items}",
	'id'=>'detailgrid',
    'columns' =>
        array(
        	array(
                    'header'=>'No',    
                    'class'=>'IndexColumn',
                    'htmlOptions'=>array('width'=>'50px'),
                ),
            array('name'=>'tanggal','value'=>'date("d-m-Y", strtotime($data["tanggalsetoran"]))','header'=>'Tanggal',),		       	
			array(
				'header'=>'Jumlah',
	            'name' => 'jumlah',	            
	            'value' => 'number_format($data["jumlah"])'
	        ),		
			array(
							                    'header'=>'Action',
							                    'class'=>'booster.widgets.TbButtonColumn',
							                    'template'=>'{update}',
							                                    'buttons' => array(
							                                        'update' =>   array(
							                                                       'label'=>'Update',
																				   'icon'=>'fa fa-pencil',
																					'url'=>'Yii::app()->createUrl("admin/transfermasuk/checkupdate", array("id"=>$data->id))', 							                                                       
							                                                        'options'=>array(
																						'onclick'=>'t();',
																						'class'=>'btn btn-success btn-xs',
							                                                            'ajax'=>array(                                                                                                                        
							                                                                'type'=>'POST',
							                                                            	'dataType'=>'json',
							                                                                'url'=>"js:$(this).attr('href')",
							                                                                'success'=>'function(data) {
																									
																								}'
							                                                            ),
							                                                        ),
							                                                   ),																				
							                                    ),
							            ),	
    ),
));
?>
