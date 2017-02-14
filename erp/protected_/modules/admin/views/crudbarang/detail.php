<table class="table table-bordered">
    <tr><th>No</th><th>Supplier</th><th>Harga Modal</th><th>Harga Eceran</th><th>Harga Grosir</th><th>Aksi</th></tr>
    <?php 
    $no=1;
    for($i=0;$i<count($child);$i++)
    {
    ?>
    <tr>
        <td><?php echo $no++;  ?></td>
        <td><?php echo Supplier::model()->findByPk($child[$i]["supplierid"])->namaperusahaan;  ?></td>        
        <td><?php echo number_format(Hargabarang::model()->find('barangid='.$child[$i]["barangid"].' AND supplierid='.$child[$i]["supplierid"])->hargamodal); ?></td>
        <td><?php echo number_format(Hargabarang::model()->find('barangid='.$child[$i]["barangid"].' AND supplierid='.$child[$i]["supplierid"])->hargaeceran); ?></td>
        <td><?php echo number_format(Hargabarang::model()->find('barangid='.$child[$i]["barangid"].' AND supplierid='.$child[$i]["supplierid"])->hargagrosir); ?></td>        
        <td>
            <?php echo CHtml::link('Update',array('crudbarang/formupdate','supplierid'=>$child[$i]["supplierid"],'barangid'=>$child[$i]["barangid"]),array('class'=>'btn btn-primary')); ?>
            <?php echo CHtml::link('Delete',array('crudbarang/deletebarangpersupplier','supplierid'=>$child[$i]["supplierid"],'barangid'=>$child[$i]["barangid"]),array('class'=>'btn btn-danger')); ?>
        </td>
    </tr>
    <?php 
    }
    ?>
</table>

<?php echo CHtml::link('Tambah Supplier dan Harga Barang', array('formsethargapersupplier','id'=>$barangid), array('class'=>'btn btn-warning btn-xs')); ?>