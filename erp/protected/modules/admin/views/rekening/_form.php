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
                'id'=>'rekening-form',
                //'htmlOptions' => array('enctype' => 'multipart/form-data'),
        		'enableClientValidation'=>true,
        		'clientOptions'=>array(
        				'validateOnSubmit'=>true,
        				'validateOnChange'=>false,
        				//'afterValidate'=>'js:myAfterValidateFunction'
        		)
        		
        )); ?>
	

	<?php echo $form->errorSummary($model); ?>
	
	<?php echo $form->textFieldGroup($model, 'namabank',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'))); ?> 

	<?php echo $form->textFieldGroup($model, 'norekening',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'))); ?> 

        <?php echo $form->textFieldGroup($model, 'namapemilik',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'))); ?> 
<span id="loadingProses" style="visibility: hidden; margin-top: -5px;">
        <h5><b>Silahkan Tunggu Proses ...</b></h5>
        <div class="progress progress-striped active">
            <div style="width: 100%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="100" role="progressbar" class="progress-bar progress-bar-danger">
                <span class="sr-only">Silahkan Tunggu..</span>
            </div>
        </div>
    </span>                           

	

	<div style="float:left">
            
		<?php 
                echo CHtml::ajaxSubmitButton('Simpan',CHtml::normalizeUrl(array('rekening/create')),
             array(
                 'dataType'=>'json',
                 'type'=>'POST',                      
                 'success'=>'js:function(data) 
                  {                   
                    document.getElementById("loadingProses").style.visibility = "hidden";           
                    if(data.result==="OK")
                    {             
                        window.location.reload();           		             			             			             			             			             			             
                    }
                    else
                    {                        
                        $.each(data, function(key, val) 
                        {
                            $("#rekening-form #"+key+"_em_").text(val);                                                    
                            $("#rekening-form #"+key+"_em_").show();
                        });
                    }       
                }'               
                 ,                                    
                 'beforeSend'=>'function()
                  {                        
                       document.getElementById("loadingProses").style.visibility = "visible";
             			
                  }'
                 ),array('id'=>'btnSave','class'=>'btn btn-primary'));                                                         
         ?> 
	</div>

<?php $this->endWidget(); ?>
