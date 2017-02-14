<?php   
        $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
                'type'=>'horizontal',
                'id'=>'transfer-form',
                'enableAjaxValidation'=>true,
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
                                                                'id'=>'Transferkasir_Tanggal_index'
                                                            )
                                                )
                        ) 
                ); 
        ?>

        <?php                     
                echo $form->dropDownListGroup($model, 'rekeningid',array(
                        'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                        'widgetOptions' => array(
                                            'data' => CHtml::listData(Rekening::model()->findAll(),'id','namabank'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Rekening',
                                                    'onchange'=>'getNorek()'
                                            )
                                    )));
        ?>

        <?php                     
                echo $form->dropDownListGroup($model, 'kategori',array(
                        'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                        'widgetOptions' => array(
                                            'data' => array(
                                                    'angsuran'=>'Angsuran',
                                                    'biaya'=>'Biaya',
                                                    'pembayaran'=>'Pembayaran',
                                            ),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Kategori',                                                    
                                            )
                                    )));
        ?>

        <div class="form-group">
            <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">No. Rekening</label>
            <div class="col-sm-4">
                <input type="text" name="norekening" id="norekening" disabled="disabled" class="form-control">                
                <div class="help-block error" id="Bongkarmuat_jumlahkaryawan_em_" style="display: block;"></div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">Nama Pemilik</label>
            <div class="col-sm-4">
                <input type="text" name="namapemilik" id="namapemilik" disabled="disabled" class="form-control">                
            <div class="help-block error" id="Bongkarmuat_jumlahkaryawan_em_" style="display: block;"></div>
            </div>
        </div>

        <?php echo $form->textFieldGroup($model, 'nama',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'))); ?> 
        

        <?php echo $form->textFieldGroup($model, 'jumlah',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'))); ?> 

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
        echo CHtml::ajaxSubmitButton('Simpan',CHtml::normalizeUrl(array('transferlain/create','render'=>true)),
            array(
                'dataType'=>'json',
                'type'=>'POST',                      
                'success'=>'js:function(data) 
                 {    
                   if(data.result==="OK")
                   {             
                      location.href="'.Yii::app()->baseurl.'/admin/transferlain";
                   }
                   else
                   {                        
                       $.each(data, function(key, val) 
                       {
                           $("#transfer-form #"+key+"_em_").text(val);                                                    
                           $("#transfer-form #"+key+"_em_").show();
                       });
                       document.getElementById("loadingProses").style.visibility = "hidden";
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
function getNorek()
{
    var rekeningid = $('#Transferlain_rekeningid option:selected').val();    
    
    var data = 'rekeningid='+rekeningid;
    
    $.ajax({
            dataType: 'json',
            type: 'POST',
            url: '<?php echo Yii::app()->baseurl; ?>/admin/transferkasir/getnorek',
            data: data,
            success: function (row) {                                                      
                
                $('#norekening').val(row.data.norekening);
                $('#namapemilik').val(row.data.namapemilik);
            },
            error: function () {
                alert("Error occured. Please try again (getLokasiBarang)");
            }
    });
}

function upah(p)
{
    var biaya = $('#biayainput').val();
    var bbm = p.value;
    
    $('#bbminput').val(bbm);
    
    var upah=(biaya-bbm)*30/100;
    $('#gaji').val(upah);
    $('#gajiinput').val(upah);
    
}
</script>