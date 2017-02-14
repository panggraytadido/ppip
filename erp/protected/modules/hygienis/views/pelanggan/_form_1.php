

<?php   $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
               // 'action'=>Yii::app()->createAbsoluteUrl("oemmkt/manajemencustomer/Ajaxvalidatecustomerprofile"),//Yii::app()->createUrl($this->route),
                //'method'=>'post',
                'enableAjaxValidation'=>true,
                'type'=>'horizontal',
                'id'=>'customer-form',
                //'htmlOptions' => array('enctype' => 'multipart/form-data'),
        		'enableClientValidation'=>true,
        		'clientOptions'=>array(
        				'validateOnSubmit'=>true,
        				'validateOnChange'=>false,
        				//'afterValidate'=>'js:myAfterValidateFunction'
        		)
        		
        )); ?>
	

	<?php echo $form->errorSummary($model); ?>
	

	<?php 
                $connection=Yii::app()->db;
                $sql="select max(kode) as kode from master.pelanggan ";					

                $data=$connection->createCommand($sql)->queryRow();
                $maxKode = substr($data["kode"], 3, 3)+1;
                
                $kode = "PL-".$maxKode;
                echo $form->textFieldGroup($model, 'kode',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-2'),
                                    'widgetOptions' => array(
                                            'htmlOptions' => array(                                                   
                                                    'value'=>$kode,
                                                    'readonly'=>'readonly',
                                            )
                                    ),                                  
                            )
                ); 
        ?>

	<?php              
                echo $form->textFieldGroup($model, 'nama',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-8'),
                                    'widgetOptions' => array(
                                            'htmlOptions' => array(         
                                                'class'=>'col-sm-8'
                                            )
                                    ),                                  
                            )
                ); 
        ?>

	<?php     
                echo $form->dropDownListGroup($model, 'propinsiid',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Propinsi::model()->findAll(),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Propinsi',										
                                            )
                                    )));              
				?>
        <?php     
                echo $form->dropDownListGroup($model, 'kotaid',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Kota::model()->findAll(),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Kota',										
                                            )
                                    )));              
				?> 

        <?php echo $form->textAreaGroup($model, 'alamat',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'))); ?>                         


	<div style="float:left">
            
		<?php 
                echo CHtml::ajaxSubmitButton('Tambah',CHtml::normalizeUrl(array('pelanggan/create','render'=>true)),
             array(
                 'dataType'=>'json',
                 'type'=>'POST',                      
                 'success'=>'js:function(data) 
                  {                   
                    $("#AjaxLoader").hide();                    
                    if(data.result==="OK")
                    {             
                        window.location.reload();           		             			             			             			             			             			             
                    }
                    else
                    {                        
                        $.each(data, function(key, val) 
                        {
                            $("#customer-form #"+key+"_em_").text(val);                                                    
                            $("#customer-form #"+key+"_em_").show();
                        });
                    }       
                }'               
                 ,                                    
                 'beforeSend'=>'function()
                  {                        
                       $("#AjaxLoader").show();
             			
                  }'
                 ),array('id'=>'btnSave','class'=>'btn btn-primary'));                                                         
         ?> 
	</div>
<br>
<?php $this->endWidget(); ?>
