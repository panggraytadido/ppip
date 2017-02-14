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
                                                                'id'=>'Transferkasir_Tanggal_index',
																'onchange'=>'getTotalKeuanganHarian();'
                                                            )
                                                )
                        ) 
                ); 
        ?>
		
		<div class="form-group">
            <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">Total Keuangan Hari ini</label>
            <div class="col-sm-4">
                <input type="text" name="totalkeuanganupdate" id="totalkeuanganupdate" value="<?php echo $jumlahkeuanganharian ?>" disabled="disabled" class="form-control">                                
            </div>
        </div>

        <?php                     
                echo $form->dropDownListGroup($model, 'rekeningid',array(
                        'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                        'widgetOptions' => array(
                                            'data' => CHtml::listData(Rekening::model()->findAll(),'id','namabank'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Rekening',
                                                    'onchange'=>'getNorek(this);getSaldoRek(this);'
                                            )
                                    )));
        ?>

        <div class="form-group">
            <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">No. Rekening</label>
            <div class="col-sm-4">
                <input type="text" name="norekeningupdate" id="norekeningupdate" value="<?php echo $rek->norekening ?>" disabled="disabled" class="form-control">                
                <div class="help-block error" id="Bongkarmuat_jumlahkaryawan_em_" style="display: block;"></div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">Nama Pemilik</label>
            <div class="col-sm-4">
                <input type="text" name="namapemilikupdate" id="namapemilikupdate" value="<?php echo $rek->namapemilik ?>" disabled="disabled" class="form-control">                
            <div class="help-block error" id="Bongkarmuat_jumlahkaryawan_em_" style="display: block;"></div>
            </div>
        </div>
		
		<div class="form-group">
            <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">Saldo Rekening</label>
            <div class="col-sm-3">
                <input type="text" name="saldorekupdate" id="saldorekupdate" value="<?php echo $saldorek ?>" disabled="disabled" class="form-control">                
            <div class="help-block error" id="Bongkarmuat_jumlahkaryawan_em_" style="display: block;"></div>
            </div>
        </div>
        

        <?php echo $form->textFieldGroup($model, 'jumlah',array(
			'wrapperHtmlOptions'=>array(
				'class'=>'col-sm-3'				
				),
				'widgetOptions' => array(
								'htmlOptions'=>array(
										//'readonly'=>'readonly',     										
										'oninput'=>'check(this);'
									)
						)	
				)); ?> 			

		<!--		
		<div class="form-group">
            <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">Total Saldo Rekening</label>
            <div class="col-sm-3">
                <input type="text" name="totalsaldorekupdate" id="totalsaldorekupdate" disabled="disabled" class="form-control">                
            <div class="help-block error" id="Bongkarmuat_jumlahkaryawan_em_" style="display: block;"></div>
            </div>
        </div>		
		-->	

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
        echo CHtml::ajaxSubmitButton('Ubah',CHtml::normalizeUrl(array('transferkasir/update','id'=>$model->id)),
            array(
                'dataType'=>'json',
                'type'=>'POST',                      
                'success'=>'js:function(data) 
                 {    
                   if(data.result==="OK")
                   {             
                      location.href="'.Yii::app()->baseurl.'/admin/transferkasir/view/id/" + data.id;
                   }
                   else
                   {                        
                       $.each(data, function(key, val) 
                       {
                           $("#transfer-form #"+key+"_em_").text(val);                                                    
                           $("#transfer-form #"+key+"_em_").show();
                       });
                   }       
                   document.getElementById("loadingProses").style.visibility = "hidden";
               }'               
                ,                                    
                'beforeSend'=>'function()
                 {        
					  $("body").undelegate("#btnUpdateKasir","click");
                      document.getElementById("loadingProses").style.visibility = "visible";

                 }'
            ),array('id'=>'btnUpdateKasir','class'=>'btn btn-primary'));
    ?> 
</div>

<br />

<?php $this->endWidget(); ?>

<script>
function check(p)
{	
	var totalkeuangan = $('#totalkeuanganupdate').val();          
	var jumlah = p.value;
	
	if(parseInt(jumlah)>parseInt(totalkeuangan))
	{
		alert('JUMLAH TIDAK BOLEH LEBIH BESAR DARI TOTAL KEUANGAN')
		$('#btnSaveKasir').prop( "disabled", true);
	}	
	else		
	{
		$('#btnSaveKasir').prop( "disabled", false);		
		var saldorek = $('#saldorekupdate').val();
		
		var totalsaldo = parseInt(saldorek)+parseInt(jumlah);		
		$('#totalsaldorekupdate').val(totalsaldo);
			
	}
}

function getTotalKeuanganHarian()
{
	var tanggal = $('#Transferkasir_Tanggal_index').val();
	var data = 'tanggal='+tanggal;
	if(tanggal!='')
	{
		 $.ajax({
            dataType: 'json',
            type: 'POST',
            url: '<?php echo Yii::app()->baseurl; ?>/admin/transferkasir/getsaldokeuanganharian',
            data: data,
            success: function (row) {                                                      
                
                $('#totalkeuanganupdate').val(row.saldo);               
            },
            error: function () {
                alert("Error occured. Please try again (getLokasiBarang)");
            }
		});
	}
}

function getNorek(p)
{
    var rekeningid = p.value;//$('#Transferkasir_rekeningid').val();    
    
    var data = 'rekeningid='+rekeningid;
    
    $.ajax({
            dataType: 'json',
            type: 'POST',
            url: '<?php echo Yii::app()->baseurl; ?>/admin/transferkasir/getnorek',
            data: data,
            success: function (row) {                                                      
                
                $('#norekeningupdate').val(row.data.norekening);
                $('#namapemilikupdate').val(row.data.namapemilik);
            },
            error: function () {
                alert("Error occured. Please try again (getLokasiBarang)");
            }
    });
}

function getSaldoRek(p)
{
	var rekeningid = p.value;//$('#Transferkasir_rekeningid').val();    
    
    var data = 'rekeningid='+rekeningid;
    
    $.ajax({
            dataType: 'json',
            type: 'POST',
            url: '<?php echo Yii::app()->baseurl; ?>/admin/transferkasir/getsaldorek',
            data: data,
            success: function (row) {                                                                      
                $('#saldorekupdate').val(row.saldo);                
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