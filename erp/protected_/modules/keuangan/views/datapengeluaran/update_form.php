<?php   
        $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
                'type'=>'horizontal',
                'id'=>'dataperjalanan-form',
                //'enableAjaxValidation'=>true,
            'enableClientValidation'=>true,
                'clientOptions'=>array(
                                'validateOnSubmit'=>true,
        				'validateOnChange'=>false,
                )
        )); 
?>
	
<?php echo $form->errorSummary($model); ?>

<input type="hidden" name="hargasatuan" id="hargasatuan" value="<?php echo $model->hargasatuan ?>">
<input type="hidden" name="jumlah" id="jumlah" value="<?php echo $model->jumlah ?>">
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
                                                        'oninput'=>'changeValHargaSatuan(this);'
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
                                                         'oninput'=>'changeValJumlah(this);hitungTotal(this);'
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
                                                )
                                    )
                            )
); ?>
      
        
        
<div style="float:left">            
<?php 
    echo CHtml::ajaxSubmitButton('Simpan',CHtml::normalizeUrl(array('datapengeluaran/update','id'=>$model->id)),
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
        ),array('id'=>'btnUpdate','class'=>'btn btn-primary'));
?> 
</div>

<br />

<?php $this->endWidget(); ?>

<script>
function hitungTotal(p)
{    
    var hargasatuan = $('#hargasatuan').val();
    var jumlah = $('#jumlah').val();
        
    //alert(jumlah);    
    var total = hargasatuan*jumlah;
    $('#Dataperjalanan_total').val(total);    
    
}

function changeValHargaSatuan(p)
{    
    var hargasatuan = p.value;
    $('#hargasatuan').val(hargasatuan);
}

function changeValJumlah(p)
{  
    var jumlah =p.value;
    $('#jumlah').val(jumlah);    
}
</script>