<table class="table table-bordered">
    <tr><th>No</th><th>Tanggal</th><th>Jumlah</th></tr>
    <?php 
    $no=1;
    for($i=0;$i<count($child);$i++)
    {
    ?>
    <tr>
        <td><?php echo $no++;  ?></td>
        <td><?php echo date("d-m-Y", strtotime($child[$i]["tanggal"])); ?></td>        
        <td><?php echo $child[$i]["jumlah"]; ?></td>        
    </tr>
    <?php 
    }
    ?>
</table>
