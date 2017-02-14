
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
	

		<?php //echo $form->errorSummary($model); ?>
		<input type="hidden" name="pelangganid" id="pelangganid" value="<?php echo $pelangganid; ?>" >

        <div class="form-group">
            <label class="col-lg-3 control-label">No Faktur</label>
            <div class="col-lg-5">
                <input class="form-control" disabled="disabled" value="<?php echo Faktur::model()->find("tanggal='$tanggal' AND  pelangganid=".$pelangganid)->nofaktur; ?>">
            <input type="hidden" id="faktur" value="<?php echo Faktur::model()->find("tanggal='$tanggal' AND  pelangganid=".$pelangganid)->nofaktur; ?>" >
            </div>
        </div>

	<div class="form-group">
            <label class="col-lg-3 control-label">Tanggal Faktur</label>
            <div class="col-lg-3">
            <input class="form-control" disabled="disabled" value="<?php echo $tanggal; ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label">Pelanggan</label>
            <div class="col-lg-6">
            <input class="form-control" disabled="disabled" value="<?php echo Pelanggan::model()->findByPk($pelangganid)->nama; ?>">
            </div>
        </div>

	<div class="form-group">
            <label class="col-lg-3 control-label">Total Harga</label>
            <div class="col-lg-3">
                <input class="form-control" disabled="disabled" value="<?php echo number_format($totalHarga) ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-3 control-label">Sisa</label>
            <div class="col-lg-3">
                <input type="hidden" name="sisainput" id="sisainput" value="<?php echo $sisa ?>" >
                <input class="form-control" disabled="disabled" id="sisa" value="<?php echo number_format($sisa) ?>">
            </div>
        </div>
		
		<div class="form-group">
            <label class="col-lg-3 control-label">Tanggal Setoran</label>
            <div class="col-lg-3">
                <?php     $this->widget(
								'booster.widgets.TbDatePicker',
								array(
									'name' => 'tanggalsetoran',
									'htmlOptions' => array('class'=>'form-control col-md-4'),
								)
							); ?>
            </div>
        </div>
		
		
		<div class="form-group">
            <label class="col-lg-3 control-label">Jenis Pembayaran</label>
            <div class="col-lg-3">
                <select class="form-control" name="jenispembayaran" onchange="jenisbayar(this);">
					<option value="0">Pilih Pembayaran</option>
					<option value="1">Setor</option>
					<option value="2">Transfer</option>
				</select>
            </div>
        </div>
		
		<div id="transfer">
			
				<div class="form-group">
                        <label class="col-lg-3 control-label required" for="Slipgajikaryawan_jeniskaryawan">Rekening</label>
                        <div class="col-lg-3">
                            <?php 
                                echo CHtml::dropDownList('rekeningid', 'rekeningid', 
                                               CHtml::listData(Rekening::model()->findAll(),'id','namabank'),
                                                array('class'=>'form-control','prompt'=>'Pilih Rekening','onchange'=>'getJumlahNoRek();')
                                            ); 
                            ?>
                        </div>
				</div>
					
				<div class="form-group">
					<label class="col-lg-3 control-label">Saldo</label>
					<div class="col-lg-3">
					<input class="form-control" name="saldo" id="saldo" readonly="readonly">					        
					</div>
					<div class="col-lg-6"><div id="messBayar"></div></div>
			   </div>
		</div>

        <div class="form-group">
            <label class="col-lg-3 control-label">Bayar</label>
            <div class="col-lg-3">
            <input class="form-control" name="bayar" id="bayar" oninput="checkBayar(this)">
            <a href="#" onclick="checkDataPembayaran();">Cek Data Pembayaran</a>            
            </div>
            <div class="col-lg-6"><div id="messBayar"></div></div>
       </div>                

	<div style="float:left">
            
		<?php 
                            echo CHtml::ajaxSubmitButton('Simpan',CHtml::normalizeUrl(array('datasetoran/setor','id'=>$id)),
                                array(
                                    'dataType'=>'json',
                                    'type'=>'POST',                      
                                    'success'=>'js:function(data) 
                                     {    

                                       if(data.result==="OK")
                                       {             
                                          location.href="'.Yii::app()->baseurl.'/kasir/datasetoran/redirecttoindex" ;
                                       }
                                       else if(data.result==="LUNAS")
                                       {
                                            location.href="'.Yii::app()->baseurl.'/kasir/datasetoran/printfaktur/pelangganid/" + data.pelangganid+"/tanggal/" +data.tanggal+"/pembelianke/" +data.pembelianke;
                                       }
                                       else if(data.result==="FAIL")
                                       { 
                                           $("#messBayar").css({"color":"red"});
                                           $("#messBayar").html("Masukan Jumlah Pembayaran");
                                       }       
                                   }'               
                                    ,                                    
                                    'beforeSend'=>'function()
                                     {                  
										  $("body").undelegate("#btnSimpanSetoran","click");
                                          $("#AjaxLoader").show();

                                     }'
                                ),array('id'=>'btnSimpanSetoran','class'=>'btn btn-primary'));      
                        ?> 
	</div>
<br>

<?php $this->endWidget(); ?>

<script>
	$("#transfer").hide();
	function jenisbayar(p)
	{
		if(p.value==1)
		{
			$("#transfer").hide();
		}
		if(p.value==2)
		{
			$("#transfer").show();
		}
	}
	
	function getJumlahNoRek()
	{
		var rekeningid = $('#rekeningid option:selected').val();    
		
		var data = 'rekeningid='+rekeningid;
		
		$.ajax({
				dataType: 'json',
				type: 'POST',
				url: '<?php echo Yii::app()->baseurl; ?>/kasir/datasetoran/getsaldorek',
				data: data,
				success: function (row) {                                                      
					
					$('#saldo').val(row.saldo);            
					if(row.saldo==0)
					{
						$('#btnSave').prop( "disabled", true);
					}
						
				},
				error: function () {
				   // alert("Error occured. Please try again (getLokasiBarang)");
				}
		});
	}
	
    function checkBayar(val)
    {
        var bayar = val.value;
        $("#messBayar").html('');
        var sisa = $("#sisainput").val();        
        if(parseInt(bayar)>parseInt(sisa))        
        {
            $("#messBayar").css("color", "red");
            $("#messBayar").html('Setoran tidak boleh lebih besar dari sisa');     
            $("#btnSave").attr("disabled", true);
        }
        else 
        {
            $("#btnSave").attr("disabled", false);
        }
    }
    
    function checkDataPembayaran()
    {
        var faktur = $('#faktur').val();
        var data ='faktur='+faktur;
        
            $.ajax({
               url: '<?php echo Yii::app()->baseurl; ?>/kasir/datasetoran/checkdatasetoran',
               type: 'post',
               //dataType:'json',
               data: data,
               success: function (data) {		
                   $("#messBayar").empty();
                    $("#messBayar").append(data);						                                                            		
               }
          });
    }
</script>    


 