<?php   $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
               // 'action'=>Yii::app()->createAbsoluteUrl("oemmkt/manajemencustomer/Ajaxvalidatecustomerprofile"),//Yii::app()->createUrl($this->route),
                //'method'=>'post',
                //'enableAjaxValidation'=>true,
                'type'=>'horizontal',
                'id'=>'anggota-form',
               // 'htmlOptions' => array('enctype' => 'multipart/form-data'),
        		'enableClientValidation'=>true,
        		'clientOptions'=>array(
        				//'validateOnSubmit'=>true,
        				'validateOnChange'=>true,
        				//'afterValidate'=>'js:myAfterValidateFunction'
        		)
        		
        )); ?>
	

	<?php echo $form->errorSummary($model); ?>
	

<?php echo $form->textFieldGroup($model, 'kode',array('wrapperHtmlOptions'=>array('class'=>'col-sm-6'))); ?> 		       
             
					  <?php echo $form->checkboxGroup($model,'ispemegangsaham'); ?>

	<?php echo $form->textFieldGroup($model, 'noktp',array('wrapperHtmlOptions'=>array('class'=>'col-sm-6'))); ?> 					

	<?php echo $form->textFieldGroup($model, 'nama',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'))); ?> 

        <?php     
                echo $form->dropDownListGroup($model, 'jeniskelamin',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'),'widgetOptions' => array(
                                            'data' => array('1'=>'Pria','2'=>'Wanita'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Jenis Kelamin',										
                                            )
                                    )));
        ?>        
       
	   
	   <?php     
                echo $form->dropDownListGroup($model, 'pendidikan',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'),'widgetOptions' => array(
                                            'data' => array('1'=>'SD','2'=>'SMP','3'=>'SLTA','4'=>'S1','5'=>'S2','6'=>'S3'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Pendidikan',										
                                            )
                                    )));
       ?>
       
       <?php echo $form->textAreaGroup($model, 'alamat',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'))); ?> 

      <?php echo $form->datePickerGroup($model, 'tanggalbermitra',array('wrapperHtmlOptions'=>array('class'=>'col-sm-5'))); ?>
       
	
       <?php echo $form->textFieldGroup($model, 'hp',array('wrapperHtmlOptions'=>array('class'=>'col-sm-4'))); ?> 
	   
	   <?php echo $form->textFieldGroup($model, 'telepon',array('wrapperHtmlOptions'=>array('class'=>'col-sm-4'))); ?> 
       
                     

         

	 
	
	  <?php echo $form->checkboxGroup($model,'status'); ?>
	<div style="float:left">            
		<?php 
                echo CHtml::ajaxSubmitButton('Update',CHtml::normalizeUrl(array('anggota/update','id'=>$model->id)),
             array(
                 'dataType'=>'json',
                 'type'=>'POST',                      
                 'success'=>'js:function(data) 
                  {                   
                    //$("#AjaxLoader").hide();                    
                    if(data.result==="OK")
                    {      
                        
                            window.location.reload();       
                        
                        
                    }
                    else
                    {                        
                        $.each(data, function(key, val) 
                        {
                            $("#anggota-form #"+key+"_em_").text(val);                                                    
                            $("#anggota-form #"+key+"_em_").show();
                        });
                    }       
                }'               
                 ,                                    
                 'beforeSend'=>'function()
                  {                        
                       $("#AjaxLoader").show();
             			
                  }'
                 ),array('id'=>'btnUpdates','class'=>'btn btn-warning'));                                                         
         ?> 
	</div>
<br>
<br>
<br>        
<br>
<?php $this->endWidget(); ?>
