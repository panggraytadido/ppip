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

<table class="table table-advance" id="table-faktur">
    <tr>
        <th>No</th>
        <th>No. Faktur</th>
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
    <tr>
        <td><?php echo $no; ?></td>
        <td>
            <?php echo $faktur[$i]['nofaktur']; ?>
            <input type="hidden" name="fakturid[<?php echo $i; ?>]" id="fakturid" value="<?php echo $faktur[$i]['id']?>">
            <input type="hidden" name="pelangganid" id="pelangganid" value="<?php echo $pelangganid; ?>">
        </td>
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
        <td><b>Total</b></td>
        <td><input type="hidden" name="totalinput" id="totalinput"><div id="total"></div></td>
    </tr>
    <tr><td><button class="btn btn-primary" onclick="simpan();">Simpan</button></td></tr>
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
        $.ajax({
                        dataType:'json',
                        type: 'POST',      
                        beforeSend: function() {
                           $("#AjaxLoader").show();
                        },
                        url: '<?php echo Yii::app()->baseurl; ?>/admin/transfermasuk/simpansetoran',
                        data: data,//{ barangid : document.getElementById("Gudangpenjualanbarang_barangid").value, kategori : kategori, supplierid : document.getElementById("Gudangpenjualanbarang_supplierid").value},
                        success: function (row) 
                        {                      
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
                            //alert("Error occured. Please try again (hitungHargaBarang)");
                        }

        });
    }
</script>    