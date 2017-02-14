<table class="table table-bordered">
    <tr><th>No</th><th>Supplier</th><th>Harga Eceran</th><th>Harga Grosir</th></tr>
    <?php 
    $no=1;
    for($i=0;$i<count($child);$i++)
    {
    ?>
    <tr>
        <td><?php echo $no++;  ?></td>
        <td><?php echo Supplier::model()->findByPk($child[$i]["supplierid"])->namaperusahaan;  ?></td>        
        <!--<td><?php // echo number_format(Hargabarang::model()->find('barangid='.$child[$i]["barangid"].' AND supplierid='.$child[$i]["supplierid"])->hargamodal); ?></td>-->
        <td><?php echo number_format(Hargabarang::model()->find('barangid='.$child[$i]["barangid"].' AND supplierid='.$child[$i]["supplierid"])->hargaeceran); ?></td>
        <td><?php echo number_format(Hargabarang::model()->find('barangid='.$child[$i]["barangid"].' AND supplierid='.$child[$i]["supplierid"])->hargagrosir); ?></td>        
        <!--<td><?php //echo CHtml::link('Ubah Harga',array('databarang/formubahharga','supplierid'=>$child[$i]["supplierid"],'barangid'=>$child[$i]["barangid"])); ?></td>-->
    </tr>
    <?php 
    }
    ?>
</table>