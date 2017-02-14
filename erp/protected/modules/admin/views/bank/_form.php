<?php
/* @var $this BagianController */
/* @var $model Bagian */
/* @var $form CActiveForm */
?>


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
	
	<?php echo $form->textFieldGroup($model, 'kode',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'))); ?> 

	<?php echo $form->textFieldGroup($model, 'nama',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'))); ?> 

        <?php echo $form->textFieldGroup($model, 'nomorrekening',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'))); ?> 

	

	<div style="float:left">
            
		<?php 
                echo CHtml::ajaxSubmitButton('Simpan',CHtml::normalizeUrl(array('bank/create','render'=>true)),
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
                 ),array('id'=>'btnSave','class'=>'btn btn-success'));                                                         
         ?> 
	</div>

<?php $this->endWidget(); ?>
