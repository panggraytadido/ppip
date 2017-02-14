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
                'id'=>'inventaris-form',
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
                echo $form->dropDownListGroup($model, 'bagianid',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Bagian::model()->findAll(),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Bagian',										
                                            )
                                    )));
                /*
                    echo $form->select2Group($model, 'divisiid',
			array(
			'wrapperHtmlOptions' => array('class' => 'col-sm-5'),
				'widgetOptions' => array(
                                            'data' => CHtml::listData(Divisi::model()->findAll(),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Divisi',										
                                            )
                                    )
				)); 
         */
				?>

	<?php echo $form->textFieldGroup($model, 'kode',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'))); ?>         

	<?php echo $form->textFieldGroup($model, 'jumlah',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'))); ?> 

	<?php echo $form->textFieldGroup($model, 'hargasatuan',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'))); ?>  

	<div style="float:left">
            
		<?php 
                echo CHtml::ajaxSubmitButton("Edit",CHtml::normalizeUrl(array("inventaris/update","id"=>$model->id)),
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
                 ),array("id"=>"btnEdit","class"=>"btn btn-warning"));                                                         
         ?> 
	</div>
<br>
<?php $this->endWidget(); ?>




 