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
																'onchange'=>'getTotalKeuanganHarian();'        	
                                                            )
                                                )
                        ) 
                ); 
        ?>
		
		<div class="form-group">
            <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">Total Keuangan Hari ini</label>
            <div class="col-sm-4">
                <input type="text" name="totalkeuangan" id="totalkeuangan" disabled="disabled" class="form-control">                                
            </div>
        </div>


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
                                                                'oninput'=>'insertToJumlah(this);'
                                                            )
                                                )
                                        )
            ); ?>

        <input type="hidden" id="jumlahinput">    

        <?php echo $form->textFieldGroup($model, 'harga', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-3'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                //'readonly'=>'readonly',
                                                                'id'=>'Databelitunai_harga',       
                                                                'oninput'=>'insertToHarga(this);'
                                                            )
                                                )
                                        )
        ); ?>     
        
        <input type="hidden" id="hargainput">

            <?php echo $form->textFieldGroup($model, 'total', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-3'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'readonly'=>'readonly',
                                                                'id'=>'Databelitunai_total',
                                                                //'value'=>'0',                                                                
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
        echo CHtml::ajaxSubmitButton('Simpan',CHtml::normalizeUrl(array('databelitunai/create','render'=>true)),
            array(
                'dataType'=>'json',
                'type'=>'POST',                      
                'success'=>'js:function(data) 
                 {    
                   if(data.result==="OK")
                   {             
                      location.href="'.Yii::app()->baseurl.'/admin/databelitunai";
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
function getTotalKeuanganHarian()
{
	var tanggal = $('#Databelitunai_tanggal').val();
	var data = 'tanggal='+tanggal;
	if(tanggal!='')
	{
		 $.ajax({
            dataType: 'json',
            type: 'POST',
            url: '<?php echo Yii::app()->baseurl; ?>/admin/databelitunai/getsaldokeuanganharian',
            data: data,
            success: function (row) {                                                      
                
                $('#totalkeuangan').val(row.saldo);               
            },
            error: function () {
                alert("Error occured. Please try again (getLokasiBarang)");
            }
		});
	}
}

function insertToJumlah(p)
{
    $('#jumlahinput').val(p.value);
}
function insertToHarga(p)
{
    $('#hargainput').val(p.value);
    var total= $('#jumlahinput').val()*$('#hargainput').val();
    $('#Databelitunai_total').val(total);
    //alert(total);
}
function hitungTotal()
{
    var jumlah = $('#Databelitunai_jumlah').val();
    var harga = $('#Databelitunai_harga').val();
    var total = jumlah*harga;        
}    
</script>