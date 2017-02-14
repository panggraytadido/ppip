<?php 

Yii::app()->clientScript->scriptMap=array(
         //scripts that you don't need inside this view
        'jquery.js'=>false,       
);
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
	

		<?php //echo $form->errorSummary($model); ?>
		<input type="hidden" name="pelangganid" id="pelangganid" value="<?php echo $pelangganid; ?>" >

        <div class="form-group">
            <label class="col-lg-3 control-label">No Faktur</label>
            <div class="col-lg-5">
                <input class="form-control" disabled="disabled" value="<?php echo Faktur::model()->find("cast(tanggal as date)='$tanggal' AND  pelangganid=".$pelangganid." AND pembelianke=".$pembelianke." AND lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'])->nofaktur; ?>">
            <input type="hidden" id="faktur" value="<?php echo Faktur::model()->find("cast(tanggal as date)='$tanggal' AND  pelangganid=".$pelangganid." AND pembelianke=".$pembelianke." AND lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'])->nofaktur; ?>" >
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
            <label class="col-lg-3 control-label">Diskon</label>
            <div class="col-lg-3">
                <input class="form-control" disabled="disabled" value="<?php echo number_format($totalDiskon) ?>">
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
					<div class="col-lg-6"><div id="messBayar1"></div></div>
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
				if($sisa!=0)
				{
					echo CHtml::ajaxSubmitButton('Simpan',CHtml::normalizeUrl(array('datasetoran/setor','id'=>$id)),
                                array(
                                    'dataType'=>'json',
                                    'type'=>'POST',                      
                                    'success'=>'js:function(data) 
                                     {    
                                       if(data.result==="OK")
                                       {                                       
                                          refreshGrid(); 
                                          $("#modal-setoran").modal("hide");   
                                          alert("DATA SETORAN BERHASIL DITAMBAHKAN");
                                       }
                                       else if(data.result==="LUNAS")
                                       {
                                            window.open("'.Yii::app()->baseurl.'/kasir/datasetoran/printfaktur/pelangganid/"+data.pelangganid+"/tanggalpembelian/"+data.tanggalpembelian+"/pembelianke/"+data.pembelianke+"/tanggalcetak/"+data.tanggalcetak, "_blank");                                               
                                            location.href="'.Yii::app()->baseurl.'/kasir/datasetoran";
                                           //location.href="'.Yii::app()->baseurl.'/kasir/datasetoran/printfaktur/pelangganid/" + data.pelangganid+"/tanggalpembelian/" +data.tanggalpembelian+"/pembelianke/" +data.pembelianke+"/tanggalcetak/" +data.tanggalcetak;
                                       }
                                       else if(data.result==="FAIL")
                                       { 
                                            alert("Masukan SEMUA INPUTAN");
                                           //$("#messBayar1").css({"color":"red"});
                                           //$("#messBayar1").html("Masukan Jumlah Pembayaran");
                                            $("#AjaxLoader").hide();
                                            $("#btnSimpanSetoran").prop( "disabled", false); 
                                       }     
                                    document.getElementById("loadingProses").style.visibility = "hidden";
                                   }'               
                                    ,                                    
                                    'beforeSend'=>'function()
                                     {
                                           $("#btnSimpanSetoran").prop( "disabled", true); 
                                           $("body").undelegate("#btnSimpanSetoran","click");
                                           document.getElementById("loadingProses").style.visibility = "visible";

                                     }'
                                ),array('id'=>'btnSimpanSetoran','class'=>'btn btn-primary')); 
				}
				else
				{
					echo '<div class="btn btn-warning">LUNAS</div>';
				}					
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
        
        function refreshGrid()
        {
           $.fn.yiiGridView.update('grid', {
                data: $(this).serialize()
            });
            return false;
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
            $("#messBayar1").css("color", "red");
            $("#messBayar1").html('Setoran tidak boleh lebih besar dari sisa');     
            $("#btnSimpanSetoran").attr("disabled", true);
        }
        else 
        {
            $("#btnSimpanSetoran").attr("disabled", false);
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


 