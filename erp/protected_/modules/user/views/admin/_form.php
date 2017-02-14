
<?php $form=$this->beginWidget('booster.widgets.TbActiveForm', array(
    'id'=>'user-form',
    'enableAjaxValidation'=>true,
    'clientOptions'=>array(
                        'validateOnChange'=>true,
        ),
    'type' => 'horizontal',
));
?>

<?php echo $form->errorSummary($model); ?>
<?php echo $form->errorSummary($profile); ?>
<?php echo $form->errorSummary($userskaryawan); ?>

<?php
$profileFields=$profile->getFields();
if ($profileFields) {
    foreach($profileFields as $field) {
        ?>

        <?php
        if ($widgetEdit = $field->widgetEdit($profile)) {
            echo $widgetEdit;
        } elseif ($field->range) {
            echo $form->dropDownListGroup($profile,$field->varname,array('wrapperHtmlOptions' => array('class' => 'col-sm-4'),'widgetOptions'=>array('data'=>Profile::range($field->range),'htmlOptions'=>array('class'=>'col-sm-2'))));
        } elseif ($field->field_type=="TEXT") {

            echo CHtml::activeTextArea($profile,$field->varname,array('rows'=>6, 'cols'=>50));
        } else {
            echo $form->textFieldGroup($profile,$field->varname,array('wrapperHtmlOptions' => array('class' => 'col-sm-4'),'widgetOptions'=>array('htmlOptions'=>array('class'=>'col-sm-2','size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)))));
        }
        ?>
    <?php
    }
}
?>
<?php echo $form->textFieldGroup($model,'username',array('wrapperHtmlOptions' => array('class' => 'col-sm-4'),'widgetOptions'=>array('htmlOptions'=>array('class'=>'col-sm-2','maxlength'=>20)))); ?>
<?php echo $form->passwordFieldGroup($model,'password',array('wrapperHtmlOptions' => array('class' => 'col-sm-4'),'widgetOptions'=>array('htmlOptions'=>array('class'=>'col-sm-2','size'=>60,'maxlength'=>128)))); ?>
<?php echo $form->textFieldGroup($model,'email',array('wrapperHtmlOptions' => array('class' => 'col-sm-4'),'widgetOptions'=>array('htmlOptions'=>array('class'=>'col-sm-2','size'=>60,'maxlength'=>128)))); ?>
<?php echo $form->dropDownListGroup($model,'superuser',array('wrapperHtmlOptions' => array('class' => 'col-sm-4'),'widgetOptions'=>array('data'=>User::itemAlias('AdminStatus'),'htmlOptions'=>array('class'=>'col-sm-2')))); ?>
<?php echo $form->dropDownListGroup($model,'status',array('wrapperHtmlOptions' => array('class' => 'col-sm-4'),'widgetOptions'=>array('data'=>User::itemAlias('UserStatus'),'htmlOptions'=>array('class'=>'col-sm-2')))); ?>

<?php
        echo $form->dropDownListGroup($userskaryawan, 'divisiid',array(
                'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                'widgetOptions' => array(
                                    'data' => CHtml::listData(divisi::model()->findAll(),'id','nama'),
                                    'htmlOptions' => array(
                                            'prompt'=>'Pilih Divisi',
                                    )
                            )));
?>


<?php     
        echo $form->dropDownListGroup($userskaryawan, 'karyawanid',array(
                'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                'widgetOptions' => array(
                                    'data' => CHtml::listData(Karyawan::model()->findAll(),'id','nama'),
                                    'htmlOptions' => array(
                                            'prompt'=>'Pilih Karyawan',
                                    )
                            )));
?>

<?php     
        echo $form->dropDownListGroup($userskaryawan, 'lokasipenyimpananbarangid',array(
                'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                'widgetOptions' => array(
                                    'data' => CHtml::listData(Lokasipenyimpananbarang::model()->findAll('isdeleted=false'),'id','nama'),
                                    'htmlOptions' => array(
                                            'prompt'=>'Pilih Lokasi',
                                    )
                            )));
?>

<div class="pull-right">
    <?php 
        echo CHtml::ajaxSubmitButton(($model->isNewRecord) ? 'Simpan' : "Ubah",
                        ($model->isNewRecord) ? 
                            CHtml::normalizeUrl(array('admin/create')) :
                            CHtml::normalizeUrl(array('admin/update', 'id'=>$model->id)),
            array(
                'dataType'=>'json',
                'type'=>'POST',                      
                'success'=>'js:function(data) 
                 {    

                   if(data.result==="OK")
                   {             
                      location.href="'.Yii::app()->baseurl.'/user/admin/view/id/" + data.id;
                   }
                   else
                   {                        
                       $.each(data, function(key, val) 
                       {
                           $("#user-form #"+key+"_em_").text(val);                                                    
                           $("#user-form #"+key+"_em_").show();
                       });
                   }       
               }'
                ,
                'beforeSend'=>'function()
                 {                        
                      $("#AjaxLoader").show();

                 }'
            ),
            array('id'=>'btnSave','class'=>'ladda-button btn btn-primary','data-style'=>'expand-left'));
    ?> 



</div>

<div class="clearfix"></div>

<?php $this->endWidget(); ?>
