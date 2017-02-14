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

	<?php echo $form->textFieldGroup($model, 'nama',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'))); ?> 

        <?php echo $form->textFieldGroup($model, 'telp',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'))); ?> 

        <?php echo $form->textFieldGroup($model, 'website',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'))); ?> 

        <?php echo $form->textFieldGroup($model, 'email',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'))); ?> 

	<?php echo $form->textAreaGroup($model, 'alamat',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'))); ?> 
        
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
                echo CHtml::ajaxSubmitButton("Update",CHtml::normalizeUrl(array("kantor/update","id"=>$model->id)),
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
                    document.getElementById('loadingProses').style.visibility = 'hidden';
                }"               
                 ,                                   
                 "beforeSend"=>"function()
                  {                        
                       document.getElementById('loadingProses').style.visibility = 'visible';
                  }"
                 ),array("id"=>"btnUpdate","class"=>"btn btn-warning"));                                                         
         ?> 
	</div>
<br>
<?php $this->endWidget(); ?>




 