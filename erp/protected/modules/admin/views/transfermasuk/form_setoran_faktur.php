<?php 
    if(count($faktur)!=0)
    {
?>

<div class="form-group">
    <label class="col-lg-2 control-label">Pelangggan</label>
    <div class="col-lg-5">
        <?php
            echo Pelanggan::model()->findByPk($pelangganid)->nama;
        ?>
    </div>
</div>
<br>
<br>

<table class="table table-bordered" id="table-faktur">
    <tr>
        <th>No</th>
		<th>Tanggal</th>
        <th>No. Faktur</th>
        <th>Tanggal Pembelian</th>
        <th>Harga Total</th>
        <th>Bayar</th>
        <th>Sisa</th>
        <th>Jumlah Bayar</th>
    </tr>
    <?php 
    $no=1;
    for($i=0;$i<count($faktur);$i++)
    {
    ?>
    <input type="hidden" name="fakturid[<?php echo $i; ?>]" id="fakturid" value="<?php echo $faktur[$i]['id']?>">
    <input type="hidden" name="pelangganid" id="pelangganid" value="<?php echo $pelangganid; ?>">
    <tr>
        <td><?php echo $no; ?></td>
		<td><input type="text" class="datepick" name="tanggaltransfer[<?php echo $i ?>]" id="tanggal_<?php echo $no; ?>"></td>
        <td>
            <?php echo $faktur[$i]['nofaktur']; ?>            
        </td>
        <td><?php echo date("d-m-Y", strtotime($faktur[$i]['tanggal'])); ?></td>
        <td><?php echo number_format($faktur[$i]['hargatotal']) ?></td>
        <td><?php echo number_format($faktur[$i]['bayar']) ?></td>
        <td><?php echo number_format($faktur[$i]['sisa']) ?></td>
        <td><input oninput="inputTxt(this);" type="text" name="bayar[<?php echo $i ?>]" id="jumlah_bayar_<?php echo $i ?>" class="jumlah_bayar"></td>
    </tr>
    <?php 
        $no++;
    }
    ?>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><b>Total</b></td>
        <td><input type="hidden" name="totalinput" id="totalinput"><div id="total"></div></td>		
    </tr>	
	<tr>
		<td>
    <span id="loadingProsesPopUp" style="visibility: hidden; margin-top: -5px;">
        <h5><b>Silahkan Tunggu Proses ...</b></h5>
        <div class="progress progress-striped active">
            <div style="width: 100%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="100" role="progressbar" class="progress-bar progress-bar-danger">
                <span class="sr-only">Silahkan Tunggu..</span>
            </div>
        </div>
    </span>
		</td>
	</tr>	
    <tr><td><button class="btn btn-primary" id="btnSimpan" onclick="simpan();">Simpan</button></td></tr>
</table>
<?php 
    }
 else {
     echo 'tidak ada data faktur';
}
?>

<script>
    function inputTxt(txt)
    {
        var sum = 0;	
	$(".jumlah_bayar").each(function()
	{		
		sum += Number($(this).val());		
	});
        
        $('#total').html(sum);	
        
        $('#total').val(txt.value);
        $('#totalinput').val(sum);
    }
    
    function simpan()
    {
        var data = $("#table-faktur :input").serialize();        
		alert(data);
        $.ajax({
                        dataType:'json',
                        type: 'POST',      
                        beforeSend: function() {
                          document.getElementById("loadingProsesPopUp").style.visibility = "visible";
                        },
                        url: '<?php echo Yii::app()->baseurl; ?>/admin/transfermasuk/simpansetoran',
                        data: data,//{ barangid : document.getElementById("Gudangpenjualanbarang_barangid").value, kategori : kategori, supplierid : document.getElementById("Gudangpenjualanbarang_supplierid").value},
                        success: function (row) 
                        {                      
								document.getElementById("loadingProsesPopUp").style.visibility = "hidden";
                       
                                if(row.result==='OK')
                                {
                                    $('#modal-faktur').modal('toggle');    
                                    $("#AjaxLoader").hide();
                                    var totalinput = $("#totalinput").val();
                                    $("#nilaitransaksi").val('');
                                    $("#sisatransaksi").val('');
                                    $("#nilaitransaksi").val(totalinput);
                                    $("#sisatransaksi").val(row.sisaTransaksi);
                                    
                                    $("#nilaitransaksiinput").val(totalinput);
                                    
                                    $('#totalsaldo').val(parseInt($('#saldoinput').val())+parseInt(totalinput));
                                    $('#totalsaldoinput').val(parseInt($('#saldoinput').val())+parseInt(totalinput));
                                }                                    
                                
                        },
                        error: function () {
                            alert("Error occured.");
                        }

        });
    }
	
	$('.datepick').each(function(){
		$(this).datepicker();
	});
</script>    