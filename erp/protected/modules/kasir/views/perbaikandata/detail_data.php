<?php 
    if(count($data)>0)
    {
?>
        Posisi Data : <?php 
                            if($jenis->jenispembayaran=="tunai")
                            {
                                echo 'INBOX';
                            }
                            else
                            {
                                echo 'Data Setoran';
                            }
                      ?>
        <table class="table table-bordered">
            <tr><th>No</th><th>Divisi</th><th>Barang</th><th>Jumlah</th></tr>
            <?php 
            $no=1;

            for($i=0;$i<count($data);$i++)
            {
            ?>
            <tr>
                <td><?php echo $no++;  ?></td>
                <td><?php echo Divisi::model()->findByPk($data[$i]["divisiid"])->nama;  ?></td>                
                <td><?php echo Barang::model()->findByPk($data[$i]["barangid"])->nama;  ?></td>       
                 <td><?php echo $data[$i]["jumlah"];  ?></td>   
            </tr>
            <?php 
            }
            ?>
        </table>
    <br>
    <?php
    }
    else {
        echo 'Tidak Ada Data';
    }
    ?>
