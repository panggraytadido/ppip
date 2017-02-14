<?php 
    if($data!='')
    {
?>

<table class="table table-advance">
    <tr>
        <th>No</th>
        <th>Tanggal Setoran</th>
        <th>Jumlah</th>
    </tr>
    <?php 
    $no=1;
    for($i=0;$i<count($data);$i++)
    {
    ?>
    <tr>
        <td><?php echo $no; ?></td>
        <td><?php echo date("d-m-Y", strtotime($data[$i]['tanggalsetoran'])); ?></td>
        <td><?php echo number_format($data[$i]['jumlah']) ?></td>
    </tr>
    <?php 
        $no++;
    }
    ?>
</table>
<?php 
    }
 else {
     echo 'belum ada data pembayaran';
}
?>