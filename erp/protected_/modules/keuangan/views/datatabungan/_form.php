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
                echo $form->dropDownListGroup($model, 'kategori',array(
                        'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                        'widgetOptions' => array(
                                            'data' => array(
                                                'pinjaman'=>'Pinjaman',
                                                'tabungan'=>'Tabungan',
                                                'cicilan'=>'Cicilan'
                                            ),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Kategori',
                                            )
                                    )));
        ?>
        
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
        echo CHtml::ajaxSubmitButton('Simpan',CHtml::normalizeUrl(array('datatabungan/create','render'=>true)),
            array(
                'dataType'=>'json',
                'type'=>'POST',                      
                'success'=>'js:function(data) 
                 {    
                   if(data.result==="OK")
                   {             
                      location.href="'.Yii::app()->baseurl.'/keuangan/datatabungan";
                   }
                   else
                   {                        
                       $.each(data, function(key, val) 
                       {
                           $("#form #"+key+"_em_").text(val);                                                    
                           $("#form #"+key+"_em_").show();
                       });
                   } 
                   document.getElementById("loadingProses").style.visibility = "hidden";
               }'               
                ,                                    
                'beforeSend'=>'function()
                 {                        
                      document.getElementById("loadingProses").style.visibility = "visible";

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