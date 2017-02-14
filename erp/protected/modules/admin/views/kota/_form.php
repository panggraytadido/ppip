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

	<?php     
                echo $form->dropDownListGroup($model, 'propinsiid',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Propinsi::model()->findAll(),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Propinsi',										
                                            )
                                    )));              
				?>

	<?php echo $form->textFieldGroup($model, 'kode',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'))); ?> 

	<?php echo $form->textFieldGroup($model, 'nama',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'))); ?> 
	

	<div style="float:left">
            
		<?php 
                echo CHtml::ajaxSubmitButton("Tambah",CHtml::normalizeUrl(array("kota/create")),
             array(
                 "dataType"=>"json",
                 "type"=>"POST",                      
                 "success"=>"js:function(data) 
                  {                   
                    $('#AjaxLoaderUpdate').hide();                    
                    if(data.result==='OK')
                    {             
                       window.location.reload();       		             			             			             			             			             			             
                    }                   
                }"               
                 ,                                   
                 "beforeSend"=>"function()
                  {                        
                       $('#AjaxLoaderUpdate').show();
             			
                  }"
                 ),array("id"=>"btnEdit","class"=>"btn btn-primary"));                                                         
         ?> 
	</div>
<br>
<?php $this->endWidget(); ?>




 