<table class="table table-bordered">
    <tr><th>No</th><th>Supplier</th><th>Lokasi Penyimpanan</th><th>Jumlah</th><th>Harga Modal</th><th>Harga Total</th></tr>
    <?php 
    $no=1;
	
    for($i=0;$i<count($child);$i++)
    {
		$totJumlah[] = $child[$i]["jumlah"];
		$hargaBarang = Hargabarang::model()->find('barangid='.$child[$i]["barangid"].' AND supplierid='.$child[$i]["supplierid"])->hargamodal ;
		$totHargaModal[] = $hargaBarang;
		
		$totalHarga[] = $child[$i]["jumlah"]*$hargaBarang;
    ?>
    <tr>
        <td><?php echo $no++;  ?></td>
        <td><?php echo Supplier::model()->findByPk($child[$i]["supplierid"])->namaperusahaan;  ?></td>
		<td><?php echo Lokasipenyimpananbarang::model()->findByPk($child[$i]["lokasipenyimpananbarangid"])->nama; ?></td>
        <td><?php echo $child[$i]["jumlah"] ?></td>
        <td><?php echo number_format($hargaBarang); ?></td>        
		<td><?php echo number_format($child[$i]["jumlah"]*$hargaBarang) ?></td>
    </tr>	
    <?php 
    }
    ?>
	 <tr>
        <td></td>
        <td></td>
		<td></td>
        <td><b></b></td>
        <td><b>Total</b></td>        
		<td><?php $c = array_sum($totalHarga); echo "<b>".number_format($c)."</b>";?></td>
    </tr>	
</table>