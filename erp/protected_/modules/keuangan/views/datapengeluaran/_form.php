<?php   
        $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
                'type'=>'horizontal',
                'id'=>'dataperjalanan-form',
                'enableAjaxValidation'=>true,
            'enableClientValidation'=>true,
                'clientOptions'=>array(
                                'validateOnSubmit'=>true,
        				'validateOnChange'=>false,
                )
        )); 
?>
	
        <?php echo $form->errorSummary($model); ?>

        <?php                     
                echo $form->dropDownListGroup($model, 'divisiid',array(
                        'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                        'widgetOptions' => array(
                                            'data' => CHtml::listData(Divisi::model()->findAll(),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Divisi',
                                            )
                                    )));
        ?>

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
        

       <?php echo $form->textFieldGroup($model, 'hargasatuan', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-3'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                //'readonly'=>'readonly',
                                                                'id'=>'Dataperjalanan_hargasatuan',                                                                
                                                            )
                                                )
                                        )
        ); ?> 

       <?php echo $form->textFieldGroup($model, 'jumlah', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-3'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                //'readonly'=>'readonly',
                                                                'id'=>'Dataperjalanan_jumlah',                                                                
                                                                'oninput'=>'hitungTotal(this);'
                                                            )
                                                )
                                        )
            ); ?>

            <?php echo $form->textFieldGroup($model, 'total', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-3'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'readonly'=>'readonly',
                                                                'id'=>'Dataperjalanan_total',
                                                                'value'=>'0',                                                                
                                                            )
                                                )
                                        )
            ); ?>
      
        
        
<div style="float:left">            
    <?php 
        echo CHtml::ajaxSubmitButton('Simpan',CHtml::normalizeUrl(array('datapengeluaran/create','render'=>true)),
            array(
                'dataType'=>'json',
                'type'=>'POST',                      
                'success'=>'js:function(data) 
                 {    
                   if(data.result==="OK")
                   {             
                      location.href="'.Yii::app()->baseurl.'/keuangan/datapengeluaran/view/id/" + data.id;
                   }
                   else
                   {                        
                       $.each(data, function(key, val) 
                       {
                           $("#dataperjalanan-form #"+key+"_em_").text(val);                                                    
                           $("#dataperjalanan-form #"+key+"_em_").show();
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
function hitungTotal(p)
{    
    var hargasatuan = $('#Dataperjalanan_hargasatuan').val();
    var jumlah = $('#Dataperjalanan_jumlah').val();
    
    //alert(hargasatuan*jumlah);
    if(hargasatuan=='')
    {
        alert('masukan harga satuan');
    }
    else
    {
        var total = hargasatuan*jumlah;
        $('#Dataperjalanan_total').val(total);
        
    }
}
</script>