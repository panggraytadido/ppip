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
                    'header'=>'Supplier',
	            'name' => 'supplierid',	            
                    'value' => function($data,$row){
                        return Supplier::model()->findByPk($data["supplierid"])->namaperusahaan;
                    }
	        ),			
                array(
                    'header'=>'Jumlah',
	            'name' => 'jumlah',	            
	            'value' => '$data["jumlah"]'
	        ),
                /* array(
                    'header'=>'Action',
                    'class'=>'booster.widgets.TbButtonColumn',
                    'template'=>'{update}',
                                    'buttons' => array(                                       
                                        'update' =>   array(
                                                       'label'=>'edit',
                                                       'icon'=>'fa fa-edit',
                                                       'url'=>'Yii::app()->createUrl("hygienis/stockupdate/formsetstock", array("id"=>$data["id"]))',
                                                        'options'=>array(
                                                            'class'=>'btn btn-success btn-xs',
                                                            'ajax'=>array(                                                                                    
                                                                'type'=>'POST',
                                                                'url'=>"js:$(this).attr('href')",
                                                                'success'=>'function(data) {
                                                                        $("#modal-update #body-update").html(data); 
                                                                        $("#modal-update").modal({backdrop: "static", keyboard: false});
                                                                }'
                                                            ),
                                                        ),
                                                    ),
                                        ),
                        ),     
							*/
                        
               
    ),
));