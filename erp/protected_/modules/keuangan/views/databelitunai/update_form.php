<?php   
        $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
                'type'=>'horizontal',
                'id'=>'form',
                //'enableAjaxValidation'=>true,
            'enableClientValidation'=>true,
                'clientOptions'=>array(
                                'validateOnSubmit'=>true,
        				'validateOnChange'=>false,
                )
        )); 
?>
	
        <?php echo $form->errorSummary($model); ?>

        
        <?php echo $form->datePickerGroup($model, 'tanggal', array(
                                'prepend' => '<i class="glyphicon glyphicon-calendar"></i>',
                                'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'readonly'=>'readonly',                                                                
                                                            )
                                                )
                        ) 
                ); 
        ?>

        <?php                     
                echo $form->dropDownListGroup($model, 'pelangganid',array(
                        'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                        'widgetOptions' => array(
                                            'data' => CHtml::listData(Pelanggan::model()->findAll(),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Pelanggan',
                                            )
                                    )));
        ?>


        <?php echo $form->textFieldGroup($model, 'nama', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-6'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                //'readonly'=>'readonly',
                                                                'id'=>'Dataperjalanan_nama',                                                             
                                                            )
                                                )
                                        )
            ); ?> 
        

       

       <?php echo $form->textFieldGroup($model, 'jumlah', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-3'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                //'readonly'=>'readonly',
                                                                'id'=>'Databelitunai_jumlah',                                                                
                                                                'oninput'=>'insertToJumlahUpdate(this);'
                                                            )
                                                )
                                        )
            ); ?>

        <input type="hidden" id="jumlahinput" value="<?php echo $model->jumlah; ?>">    

        <?php echo $form->textFieldGroup($model, 'harga', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-3'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                //'readonly'=>'readonly',
                                                                'id'=>'Databelitunai_harga',       
                                                                'oninput'=>'insertToHargaUpdate(this);'
                                                            )
                                                )
                                        )
        ); ?>     
        
        <input type="hidden" id="hargainput" value="<?php echo $model->harga; ?>">

            <?php echo $form->textFieldGroup($model, 'total', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-3'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'readonly'=>'readonly',
                                                                'id'=>'Databelitunai_totalnya',
                                                                //'value'=>'0',                                                                
                                                            )
                                                )
                                        )
            ); ?>
      
            <input type="hidden" id="totalinput" value="<?php echo $model->total; ?>">
        
<div style="float:left">            
    <?php 
        echo CHtml::ajaxSubmitButton('Update',CHtml::normalizeUrl(array('databelitunai/update','id'=>$model->id)),
            array(
                'dataType'=>'json',
                'type'=>'POST',                      
                'success'=>'js:function(data) 
                 {    
                   if(data.result==="OK")
                   {             
                      location.href="'.Yii::app()->baseurl.'/keuangan/databelitunai";
                   }
                   else
                   {                        
                       $.each(data, function(key, val) 
                       {
                           $("#form #"+key+"_em_").text(val);                                                    
                           $("#form #"+key+"_em_").show();
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

<br />

<?php $this->endWidget(); ?>

<script>
function insertToJumlahUpdate(p)
{
    $('#jumlahinput').val(p.value);
}
function insertToHargaUpdate(p)
{
    $('#hargainput').val(p.value);
    var total= $('#jumlahinput').val()*$('#hargainput').val();
    //$('#Databelitunai_total').val('');
    //$('#Databelitunai_totalnya').html(total);
    $('#Databelitunai_totalnya').val(total);
    $('#totalinput').val(total);
    //alert(total);
}
function hitungTotalUpdate()
{
    var jumlah = $('#Databelitunai_jumlah').val();
    var harga = $('#Databelitunai_harga').val();
    var total = jumlah*harga;        
}    
</script>