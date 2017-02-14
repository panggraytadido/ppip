<?php
$this->pageTitle = "Set Jenis Pembayaran - Kasir";

$this->breadcrumbs=array(
	'Kasir','Set Jenis Pembayaran'
);
?>
                     
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-lg-4 control-label">Tanggal</label>
                            <div class="col-lg-3">
                            <input class="form-control" disabled="disabled" value="<?php echo $tanggal; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label">Pelanggan</label>
                            <div class="col-lg-6">
                                <input class="form-control" disabled="disabled" value="<?php echo $pelanggan ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-4 control-label">Jenis Pembayaran</label>
                            <div class="col-lg-4">
                                <select name="jenispembayaran" id="jenispembayaran" class="form-control">
									<option value="0">Pilih Jenis Pembayaran</option>
									<option value="1">Inbox</option>
									<option value="2">Data Setoran</option>
								</select>
                            </div>
                        </div>                       
                    </form>                        
                   
                    <input type="hidden" id="pelangganid" name="pelangganid" value="<?php echo $pelangganid ?>" >
                    <input type="hidden" id="tanggal" name="tanggal" value="<?php echo $tanggal ?>" >
					<input type="hidden" id="pembelianke" name="pembelianke" value="<?php echo $pembelianke ?>" >
                    
                    <span id="loadingProses" style="visibility: hidden; margin-top: -5px;">
						<h5><b>Silahkan Tunggu Proses ...</b></h5>
						<div class="progress progress-striped active">
							<div style="width: 100%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="100" role="progressbar" class="progress-bar progress-bar-danger">
								<span class="sr-only">Silahkan Tunggu..</span>
							</div>
						</div>
					</span>
                    <div class="pull-left">
						<button class="btn btn-primary" id="btnPilihPembayaran" onclick="pilihan();">Submit</button>						
                         <?php 
							/*
                            echo CHtml::ajaxSubmitButton('Simpan',CHtml::normalizeUrl(array('inbox/setjenispembayaran','pelangganid'=>$pelangganid,'tanggal'=>$tanggal,'pembelianke'=>$pembelianke)),
                                array(
                                    'dataType'=>'json',
                                    'type'=>'POST',                      
                                    'success'=>'js:function(data) 
                                     {    

                                       if(data.result==="OK")
                                       {             
                                          location.href="'.Yii::app()->baseurl.'/kasir/datasetoran";
                                       }
                                       else
                                       {                        
                                           $.each(data, function(key, val) 
                                           {
                                               $("#gudangpenjualanbarang-form #"+key+"_em_").text(val);                                                    
                                               $("#gudangpenjualanbarang-form #"+key+"_em_").show();
                                           });
                                       }       
                                   }'               
                                    ,                                    
                                    'beforeSend'=>'function()
                                     {                        
                                          $("#AjaxLoader").show();

                                     }'
                                ),array('id'=>'btnSave','class'=>'btn btn-primary'));   */   
                        ?> 
                                                                  
                        
                    </div>  
					<br>
<script>
function pilihan()
{
	var pilihan = $('#jenispembayaran option:selected').val();
	var pelangganid  = $('#pelangganid').val();
	var tanggal  = $('#tanggal').val();	
	var pembelianke  = $('#pembelianke').val();	
	
	var data = 'pilihan='+pilihan+'&pelangganid='+pelangganid+'&tanggal='+tanggal+'&pembelianke='+pembelianke;
	if(pilihan!=0)
	{
		if(pelangganid!='' && tanggal!='')
		{
			 $.ajax({
					dataType: 'json',
					type: 'POST',
					beforeSend:function(){
						$("#btnPilihPembayaran").prop( "disabled", true);
						document.getElementById("loadingProses").style.visibility = "visible";
					},	
					url: '<?php echo Yii::app()->baseurl; ?>/kasir/inbox/setjenispembayaran',
					data: data,
					success: function (row) {   
						$("#btnPilihPembayaran").prop( "disabled", false);
						document.getElementById("loadingProses").style.visibility = "hidden";
						if(row.result=="INBOX")
						{
							location.href="<?php echo Yii::app()->baseurl; ?>/kasir/inbox/formfaktur/pelangganid/"+row.pelangganid+"/tanggal/"+row.tanggal+"/pembelianke/"+row.pembelianke;
						}		
						if(row.result=="SETORAN")
						{
							location.href="<?php echo Yii::app()->baseurl; ?>/kasir/datasetoran/index"
						}		
						
										   
					},
					error: function () {
						alert("Error occured. Please try again (getLokasiBarang)");
					}
				});	
		}		
	}
	else
	{
		alert('PILIH JENIS PEMBAYARAN');
	}
}
</script>                    